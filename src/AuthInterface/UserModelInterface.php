<?php

declare(strict_types=1);

namespace Alexzy\HyperfAuth\AuthInterface;

interface UserModelInterface
{
    public function getId();

    public function getUserById($id): ?UserModelInterface;
}