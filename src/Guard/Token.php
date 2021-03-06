<?php

declare(strict_types=1);

namespace Alexzy\HyperfAuth\Guard;

use Alexzy\HyperfAuth\AuthInterface\LoginGuardInterface;
use Alexzy\HyperfAuth\AuthInterface\UserModelInterface;
use Alexzy\HyperfAuth\Exception\AuthException;
use Alexzy\HyperfAuth\Exception\UnauthorizedException;
use Alexzy\HyperfAuth\Service\CacheService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\Context;

class Token implements LoginGuardInterface
{
    /**
     * @Inject
     * @var CacheService
     */
    protected $cache;

    /**
     * @Inject
     * @var IdGeneratorInterface
     */
    protected $idGenerator;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var UserModelInterface
     */
    protected $user;

    public function login(UserModelInterface $user)
    {
        $token = $this->makeToken($user->getId());
        if ($this->cache->getConfig()['login_one_user']) {
            $this->cleanUser($user);
        }
        $this->cache->getCache()->set($this->prefixKey($user->getId()) . $token, $user);
        return $token;
    }

    public function user(?string $token = null): ?UserModelInterface
    {

        try {
            $token = $token ?? $this->parseToken();
            if (!$token) {
                throw new UnauthorizedException('token 是必须的');
            }
            if (Context::has($token)) {
                $result = Context::get($token);
                if ($result instanceof \Throwable) {
                    throw $result;
                }
                return $result ?: null;
            }

            /** @var UserModelInterface $user */
            $user = $this->cache->getCache()->get($token);
            $uid = $user->getId() ?? null;
            $user = $uid ? $this->user->getUserById($uid) : null;
            Context::set($token, $user ?: 0);
            return $user;
        } catch (\Throwable $exception) {
            $newException = $exception instanceof AuthException ? $exception : new UnauthorizedException(
                $exception->getMessage(),
                $exception
            );
            if ($token) Context::set($token, $newException);
            throw $newException;
        }
    }

    public function check(?string $token = null): bool
    {
        try {
            return $this->user($token) instanceof UserModelInterface;
        } catch (AuthException $exception) {
            return false;
        }
    }

    public function logout($token = null)
    {
        if ($token = $token ?? $this->parseToken()) {
            Context::set($token, null);
            $this->cache->getCache()->delete($token);
            return true;
        }
        return false;
    }


    public function cleanUser(UserModelInterface $user)
    {
        $user_id = $user->getId();
        $token = $this->prefixKey($user_id);
        $this->cache->getCache()->clearPrefix($token);
        return true;
    }

    public function makeToken($user_id)
    {
        $token = md5((string)$this->idGenerator->generate());
        return base64_encode($token . $user_id);
    }

    public function prefixKey($user_id)
    {
        return 'auth:' . $user_id . ':';
    }

    public function parseToken()
    {
        $header = $this->request->header('token', '');
        if ($header) {
            $token = $header;
        }
        if ($this->request->has('token')) {
            $token = $this->request->input('token');
        }
        if (!isset($token)) {
            return null;
        }
        $user_id = substr_replace(base64_decode($token), '', 0, 32);
        return $this->prefixKey($user_id) . $token;
    }

}