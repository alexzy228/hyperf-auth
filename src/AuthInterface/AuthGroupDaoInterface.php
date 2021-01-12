<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth\AuthInterface;

interface AuthGroupDaoInterface
{
    /**
     * 根据权限组ID查询多个权限组
     * @param $ids
     * @return array
     */
    public function getGroupsById($ids): array;

    /**
     * 根据权限组父级ID查询多个权限组
     * @param $ids
     * @return array
     */
    public function getGroupsByPid($ids): array;

    /**
     * 根据权限组ID查询多个启用的权限组
     * @param $ids
     * @return array
     */
    public function getEnableGroupsById($ids): array;

    /**
     * 获取所有启用的权限组
     * @return array
     */
    public function getEnableGroups(): array;

    /**
     * 根据权限组ID获取单个权限组
     * @param $id
     * @return array
     */
    public function getOneGroupById($id): array;

    /**
     * 新增权限组
     * @param $data
     * @return mixed
     */
    public function insertGroup($data);

    /**
     * 根据ID更新权限组
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateGroupById($id, $data);

    /**
     * 根据ID删除权限组
     * @param $ids
     * @return mixed
     */
    public function deleteGroupById($ids);
}