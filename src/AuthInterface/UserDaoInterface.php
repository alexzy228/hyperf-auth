<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth\AuthInterface;


interface UserDaoInterface
{
    /**
     * 获取所有用户ID
     * @return array
     */
    public function getAllUserIds(): array;
}