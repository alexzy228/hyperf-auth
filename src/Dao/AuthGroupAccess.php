<?php

declare (strict_types=1);

namespace Ycbl\AdminAuth\Dao;

use Alexzy\HyperfAuth\AuthInterface\AuthGroupAccessDaoInterface;
use Alexzy\HyperfAuth\Model\AuthGroupAccess as Model;

class AuthGroupAccess implements AuthGroupAccessDaoInterface
{
    public function getGroupIdsByUid($uid): array
    {
        return Model::query()->where('uid', $uid)->pluck('group_id')->toArray();
    }

    public function getUsersByGroupId($group_id): array
    {
        return Model::query()->where('group_id', $group_id)->get()->toArray();
    }

    public function saveAll($data)
    {
        return Model::insert($data);
    }

    public function deleteByUid($uid)
    {
        return Model::query()->where('uid', $uid)->delete();
    }
}