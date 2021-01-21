<?php

declare(strict_types=1);

namespace Alexzy\HyperfAuth\Service;

use Alexzy\HyperfAuth\AuthInterface\AuthGroupAccessDaoInterface;
use Alexzy\HyperfAuth\AuthInterface\AuthGroupDaoInterface;
use Alexzy\HyperfAuth\AuthInterface\AuthRuleDaoInterface;
use Alexzy\HyperfAuth\AuthInterface\LoginGuardInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Psr\SimpleCache\InvalidArgumentException;

class  AuthService
{
    /**
     * @Inject
     * @var LoginGuardInterface
     */
    protected $loginGuard;

    /**
     * @Inject
     * @var AuthGroupAccessDaoInterface
     */
    protected $authGroupAccessDao;

    /**
     * @Inject
     * @var AuthGroupDaoInterface
     */
    protected $authGroupDao;

    /**
     * @Inject
     * @var AuthRuleDaoInterface
     */
    protected $authRuleDao;

    /**
     * @Inject
     * @var CacheService
     */
    protected $cache;

    private $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config->get('auth');
    }

    /**
     * 获取用户的所有启用状态权限组
     * @param string $uid
     * @return mixed|null
     */
    public function getGroups($uid = '')
    {
        $uid = $uid ? $uid : $this->loginGuard->user()->getId();
        $cacheKey = $this->config['prefix'] . '-auth_group.' . $uid;
        if (Context::has($cacheKey)) {
            return Context::get($cacheKey);
        }

        $group_ids = $this->authGroupAccessDao->getGroupIdsByUid($uid);
        $user_group = $this->authGroupDao->getEnableGroupsById($group_ids);

        Context::set($cacheKey, $user_group ?: []);
        return Context::get($cacheKey);
    }

    /**
     * 获取用户的所有权限规则ID
     * @param string $uid
     * @return array
     */
    public function getRuleIds($uid = '')
    {
        $uid = $uid ? $uid : $this->loginGuard->user()->getId();
        $groups = $this->getGroups($uid);
        $ids = [];
        foreach ($groups as $group) {
            $ids = array_merge($ids, explode(',', trim($group['rules'], ',')));
        }
        $ids = array_unique($ids);
        return $ids;
    }

    /**
     * 获取用户的所有权限规则
     * @param string $uid
     * @return array|mixed|null
     * @throws InvalidArgumentException
     */
    public function getRuleList($uid = '')
    {
        $uid = $uid ? $uid : $this->loginGuard->user()->getId();
        $cacheKey = $this->config['prefix'] . '-auth_rule_list.' . $uid;
        if (Context::has($cacheKey)) {
            return Context::get($cacheKey);
        }
        if (2 == $this->config['auth_type'] && $this->cache->getCache()->has($cacheKey)) {
            return $this->cache->getCache()->get($cacheKey);
        }

        $ids = $this->getRuleIds($uid);
        if (empty($ids)) {
            Context::set($cacheKey, []);
            return [];
        }

        $rules = $this->authRuleDao->getEnableRulesById($ids);
        $rule_list = [];
        //拥有的规则id 包含* 则直接返回*
        if (in_array('*', $ids)) {
            $rule_list[] = '*';
        }
        foreach ($rules as $rule) {
            $rule_list[$rule['id']] = $rule['auth'];
        }
        Context::set($cacheKey, $rule_list);
        if (2 == $this->config['auth_type']) {
            $this->cache->getCache()->set($cacheKey, $rule_list);
        }
        return array_unique($rule_list);
    }

    /**
     * 清除权限缓存
     * @param string $uid
     * @throws InvalidArgumentException
     */
    public function cleanCache($uid = '')
    {
        $uid = $uid ? $uid : $this->loginGuard->user()->getId();
        $cacheKey = $this->config['prefix'] . '-auth_rule_list.' . $uid;
        $this->cache->getCache()->delete($cacheKey);
    }

    /**
     * 检查权限
     * @param $name
     * @param string $uid
     * @param string $relation
     * @return bool
     * @throws InvalidArgumentException
     */
    public function check($name, string $uid = '', string $relation = 'or')
    {
        $uid = $uid ? $uid : $this->loginGuard->user()->getId();
        //权限认证开关未开启状态直接返回验证成功
        if (!$this->config['auth_on']) {
            return true;
        }
        $ruleList = $this->getRuleList($uid);
        //规则列表包含* 则直接返回验证通过
        if (in_array('*', $ruleList)) {
            return true;
        }

        //判断验证数组还是字符串，转换为数组形式
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = [$name];
            }
        }
        //保存验证通过的规则名
        $list = [];
        foreach ($ruleList as $rule) {
            if (in_array($rule, $name)) {
                $list[] = $rule;
            }
        }
        if ('or' == $relation && !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ('and' == $relation && empty($diff)) {
            return true;
        }
        return false;
    }

    /**
     * 判断当前管理员是否为超级管理员
     * @return bool
     */
    public function isSuperAdmin()
    {
        return in_array('*', $this->getRuleIds()) ? true : false;
    }
}