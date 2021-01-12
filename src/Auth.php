<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth;

use Alexzy\HyperfAuth\AuthInterface\LoginGuardInterface;
use Hyperf\Di\Annotation\Inject;

class Auth
{
    /**
     * @Inject()
     * @var LoginGuardInterface
     */
    protected $loginGuard;

    public function login()
    {
        return $this->loginGuard->login();
    }

}