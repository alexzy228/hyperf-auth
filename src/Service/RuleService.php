<?php


namespace Alexzy\HyperfAuth\Service;

use Alexzy\HyperfAuth\AuthInterface\AuthRuleDaoInterface;
use Alexzy\HyperfAuth\Exception\ErrorException;
use Alexzy\HyperfAuth\Exception\UnauthorizedException;
use Exception;
use Hyperf\Di\Annotation\Inject;

class RuleService
{
    /**
     * @Inject
     * @var AuthService
     */
    protected $authService;

    /**
     * @Inject
     * @var AuthRuleDaoInterface
     */
    protected $authRuleDao;

    public function __construct()
    {
        if (!$this->authService->isSuperAdmin()) {
            throw new ErrorException('仅超级管理组可以访问');
        }
    }

    /**
     * 获取所有权限规则
     * @param bool $has_tree
     * @return array
     */
    public function getAllRule($has_tree = false): array
    {
        $list = $this->authRuleDao->getRuleList();
        $tree = make(TreeService::class)->init($list);
        $arr_tree = $tree->getTreeArray(0);
        if ($has_tree === true) {
            return $arr_tree;
        }
        return $tree->getTreeList($arr_tree);
    }

    /**
     * 创建规则
     * @param $data
     * @return mixed
     */
    public function createRule($data)
    {
        if (!$data['ismenu'] && !$data['pid']) {
            throw new ErrorException('非菜单规则节点必须有父级');
        }
        return $this->authRuleDao->insertRule($data);
    }

    /**
     * 编辑规则
     * @param $data
     * @return mixed
     */
    public function editRule($data)
    {
        $rule = $this->authRuleDao->getOneRuleById($data['id']);
        if (!$rule) {
            throw new ErrorException('记录未找到');
        }
        if (!$data['ismenu'] && !$data['pid']) {
            throw new ErrorException('非菜单规则节点必须有父级');
        }
        if ($data['pid'] != $rule['pid']) {
            //获取当前节点的所有子节点ID
            $all_rule = $this->authRuleDao->getRuleList();
            $children_ids = make(TreeService::class)->init($all_rule)->getChildrenIds($rule['pid']);
            if (in_array($data['pid'], $children_ids)) {
                throw new ErrorException("变更的父组别不能是它的子组别");
            }
        }
        return $this->authRuleDao->updateRuleById($data['id'], $data);
    }

    /**
     * 删除规则
     * @param $ids
     * @return int|mixed
     */
    public function deleteRule($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        $del_ids = [];
        foreach ($ids as $k => $v) {
            $all_rule = $this->authRuleDao->getRuleList();
            $children_ids = make(TreeService::class)->init($all_rule)->getChildrenIds($v, true);
            $del_ids = array_merge($del_ids, $children_ids);
        }
        return $this->authRuleDao->deleteRulesById($del_ids);
    }
}