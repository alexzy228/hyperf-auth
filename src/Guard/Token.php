<?php

declare(strict_types=1);

namespace Alexzy\HyperfAuth\Guard;

use Alexzy\HyperfAuth\AuthInterface\LoginGuardInterface;
use Alexzy\HyperfAuth\AuthInterface\UserModelInterface;
use Alexzy\HyperfAuth\Service\CacheService;
use Hyperf\Di\Annotation\Inject;

class Token implements LoginGuardInterface
{
    /**
     * @Inject
     * @var CacheService
     */
    protected $cache;

    public function login(UserModelInterface $user)
    {
        $this->cache->getCache()->set('user_login', $user);
    }

    public function user(): UserModelInterface
    {
    }

    public function check(): bool
    {
        // TODO: Implement check() method.
    }

    public function logout()
    {
        // TODO: Implement logout() method.
    }
}