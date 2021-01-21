<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth;

use Alexzy\HyperfAuth\AuthInterface\LoginGuardInterface;
use Alexzy\HyperfAuth\AuthInterface\UserModelInterface;
use Alexzy\HyperfAuth\Service\AuthService;
use Hyperf\Di\Annotation\Inject;

class Auth
{
    /**
     * @Inject()
     * @var LoginGuardInterface
     */
    protected $loginGuard;

    /**
     * @Inject
     * @var AuthService
     */
    protected $authService;

    public function login(UserModelInterface $user)
    {
        return $this->loginGuard->login($user);
    }

    public function user()
    {
        return $this->loginGuard->user();
    }

    public function isLogin()
    {
        return $this->loginGuard->check();
    }

    public function check($name, $uid = '', $relation = 'or')
    {
        return $this->authService->check($name, $uid, $relation);
    }

}