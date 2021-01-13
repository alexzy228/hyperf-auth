<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth\AuthInterface;

interface LoginGuardInterface
{
    /**
     * 用户登录方法
     * @param UserModelInterface $user
     * @return mixed
     */
    public function login(UserModelInterface $user);

    /**
     * 获取用户信息方法
     * @return mixed
     */
    public function user(): ?UserModelInterface;

    /**
     * 获取用户是否登录方法
     * @return mixed
     */
    public function check():bool;

    /**
     * 退出登录方法
     * @return mixed
     */
    public function logout();

}