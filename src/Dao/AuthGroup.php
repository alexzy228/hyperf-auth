<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth\Dao;

use Alexzy\HyperfAuth\AuthInterface\AuthGroupDaoInterface;
use Alexzy\HyperfAuth\Model\AuthGroup as Model;

class AuthGroup implements AuthGroupDaoInterface
{
    public function getGroupsById($ids): array
    {
        return Model::query()->whereIn('id', $ids)->get()->toArray();
    }

    public function getGroupsByPid($ids): array
    {
        return Model::query()->whereIn('pid', $ids)->get()->toArray();
    }

    public function getEnableGroupsById($ids): array
    {
        return Model::query()->select('id', 'pid', 'name', 'rules')
            ->whereIn('id', $ids)
            ->where('status', '=', '1')
            ->get()->toArray();
    }

    public function getEnableGroups(): array
    {
        return Model::query()
            ->where('status', '=', '1')
            ->get()->toArray();
    }

    public function getOneGroupById($id): array
    {
        $result = Model::query()->where('id', $id)->first();
        if (!$result) {
            return [];
        }
        return $result->toArray();
    }

    public function insertGroup($data)
    {
        return Model::query()->insert($data);
    }

    public function updateGroupById($id, $data)
    {
        return Model::query()->where('id', $id)->update($data);
    }

    public function deleteGroupById($ids)
    {
        return Model::query()->whereIn('id', $ids)->delete();
    }
}