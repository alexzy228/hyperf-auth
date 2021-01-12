<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth\AuthInterface;


interface AuthRuleDaoInterface
{
    /**
     * 获取所有权限规则列表
     * @return array
     */
    public function getRuleList(): array;

    /**
     * 根据ID获取权限规则
     * @param $id
     * @return array
     */
    public function getOneRuleById($id): array;

    /**
     * 获取所有开启状态的菜单权限规则
     * @return array
     */
    public function getAllEnableMenu(): array;

    /**
     * 根据ID获取所有开启状态的权限规则
     * @param $ids
     * @return array
     */
    public function getEnableRulesById($ids): array;

    /**
     * 新增权限规则
     * @param $data
     * @return mixed
     */
    public function insertRule($data);

    /**
     * 根据ID更新权限规则
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateRuleById($id, $data);

    /**
     * 根据ID删除权限规则
     * @param $ids
     * @return mixed
     */
    public function deleteRulesById($ids);
}