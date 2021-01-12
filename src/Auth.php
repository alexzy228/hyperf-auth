<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth;

use Alexzy\HyperfAuth\AuthInterface\LoginGuardInterface;
use Alexzy\HyperfAuth\AuthInterface\UserModelInterface;
use Hyperf\Di\Annotation\Inject;

class Auth
{
    /**
     * @Inject()
     * @var LoginGuardInterface
     */
    protected $loginGuard;

    public function login(UserModelInterface $user)
    {
        return $this->loginGuard->login($user);
    }

    public function user()
    {
        return $this->loginGuard->user();
    }

}