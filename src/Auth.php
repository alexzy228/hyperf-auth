<?php

namespace Alexzy\HyperfAuth;

use Alexzy\HyperfAuth\AuthInterface\LoginGuard;
use Hyperf\Di\Annotation\Inject;

class Auth
{
    /**
     * @Inject()
     * @var LoginGuard
     */
    protected $loginGuard;

    public function login()
    {
        return $this->loginGuard->login();
    }

}