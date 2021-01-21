<?php


namespace Alexzy\HyperfAuth\Service;


use Hyperf\Di\Annotation\Inject;

class GroupService
{
    /**
     * @Inject
     * @var AuthService
     */
    protected $authService;

    public function getList()
    {
        $children_group_ids = $this->getChildrenGroupIds(true);
    }

    /**
     * 获取当前管理员的所有权限组ID
     * @param false $withSelf
     */
    public function getChildrenGroupIds($withSelf = false)
    {
        // 获取用户的权限组
        $groups = $this->authService->getGroups();
        // 权限组ID
        $groups_ids = array_column($groups, 'id');
        // 顶级权限组ID
        $origin_group_ids = $groups_ids;

        foreach ($groups as $k => $v) {
            if (in_array($v['pid'], $origin_group_ids)) {
                $groups_ids = array_diff($groups_ids, [$v['id']]);
                unset($groups[$k]);
            }
        }
    }
}