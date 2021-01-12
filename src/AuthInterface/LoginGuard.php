<?php

namespace Alexzy\HyperfAuth\AuthInterface;

interface LoginGuard
{
    /**
     * 用户登录方法
     * @return mixed
     */
    public function login();

    /**
     * 获取用户信息方法
     * @return mixed
     */
    public function user();

    /**
     * 获取用户是否登录方法
     * @return mixed
     */
    public function check();

    /**
     * 退出登录方法
     * @return mixed
     */
    public function logout();

}