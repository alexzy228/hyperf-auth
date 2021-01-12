<?php

declare(strict_types=1);

namespace Alexzy\HyperfAuth\Service;

class TreeService
{
    protected $pidName;
    private $tree;

    public function init(array $arr, string $pidName = 'pid')
    {
        $this->tree = $arr;
        $this->pidName = $pidName;
        return $this;
    }

    public function getArr()
    {
        return $this->tree;
    }

    /**
     * 获取指定ID的子节点
     * @param $id
     * @return array
     */
    public function getChild($id)
    {
        $new_arr = [];
        foreach ($this->getArr() as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value[$this->pidName] == $id) {
                $new_arr[$value['id']] = $value;
            }
        }
        return $new_arr;
    }

    /**
     * 获取指定ID的所有子孙节点
     * @param $id
     * @param false $with_self
     * @return array
     */
    public function getChildren($id, $with_self = false)
    {
        $new_arr = [];
        foreach ($this->getArr() as $value) {
            if (!isset($value['id'])) {
                continue;
            }
            if ($value[$this->pidName] == $id) {
                $new_arr[] = $value;
                $new_arr = array_merge($new_arr, $this->getChildren($value['id']));
            } elseif ($with_self && $value['id'] == $id) {
                $new_arr[] = $value;
            }
        }
        return $new_arr;
    }

    /**
     * 获取指定ID的所有子孙节点ID
     * @param $id
     * @param $with_self
     * @return array
     */
    public function getChildrenIds($id, $with_self)
    {
        $children_list = $this->getChildren($id, $with_self);
        $children_ids = [];
        foreach ($children_list as $k => $v) {
            $children_ids[] = $v['id'];
        }
        return $children_ids;
    }

    /**
     * 获取指定ID的父节点
     * @param $id
     * @return array
     */
    public function getParent($id)
    {
        $new_arr = [];
        foreach ($this->getArr() as $value) {
            //没有id 不会是上级节点
            if (!isset($value['id'])) {
                continue;
            }
            //查找到自己的位置
            if ($value['id'] == $id) {
                //获取到PID
                $pid = $value[$this->pidName];
                break;
            }
        }
        //如果pid存在
        if (isset($pid)) {
            foreach ($this->getArr() as $value) {
                //获取上级数组
                if ($value['id'] == $pid) {
                    $new_arr[] = $value;
                    break;
                }
            }
        }
        return $new_arr;
    }

    /**
     * 获取指定ID的所有祖父节点
     * @param $id
     * @param $with_self
     * @return array
     */
    public function getParents($id, $with_self)
    {
        $new_arr = [];
        foreach ($this->getArr() as $value) {
            //没有id 不会是上级节点
            if (!isset($value['id'])) {
                continue;
            }
            //查找到自己的位置
            if ($value['id'] == $id) {
                //如果包含自己则添加自身
                if ($with_self) {
                    $new_arr[] = $value;
                }
                $pid = $value[$this->pidName];
                break;
            }
        }
        //如果PID存在
        if (isset($pid)) {
            //递归获取上级数组
            $arr = $this->getParents($pid, true);
            $new_arr = array_merge($arr, $new_arr);
        }
        return $new_arr;
    }

    /**
     * 获取指定ID的所有祖父节点ID
     * @param $id
     * @param boolean $with_self
     * @return array
     */
    public function getParentsIds($id, $with_self = false)
    {
        $parent_list = $this->getParents($id, $with_self);
        $parents_ids = [];
        foreach ($parent_list as $k => $v) {
            $parents_ids[] = $v['id'];
        }
        return $parents_ids;
    }
}