<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth\AuthInterface;


interface AuthGroupAccessDaoInterface
{
    /**
     * 根据用户ID获取用户权限组ID
     * @param $uid
     * @return array
     */
    public function getGroupIdsByUid($uid): array;

    /**
     * 根据权限组ID获取用户列表
     * @param $group_id
     * @return array
     */
    public function getUsersByGroupId($group_id): array;

    /**
     * 批量保存权限关系
     * @param $data
     * @return mixed
     */
    public function saveAll($data);

    /**
     * 根据用户ID删除权限关系
     * @param $uid
     * @return mixed
     */
    public function deleteByUid($uid);
}