<?php

declare (strict_types=1);

namespace Alexzy\HyperfAuth\Dao;

use Alexzy\HyperfAuth\AuthInterface\AuthRuleDaoInterface;
use Alexzy\HyperfAuth\Model\AuthRule as Model;

class AuthRule implements AuthRuleDaoInterface
{
    public function getRuleList(): array
    {
        return Model::query()
            ->select(["id", "pid", "path", "auth", "title", "icon", "ismenu", "weigh", "status"])
            ->orderBy('weigh', 'DESC')->orderBy('id')
            ->get()->toArray();
    }

    public function getOneRuleById($id): array
    {
        $result = Model::query()->where('id', $id)->first();
        if (!$result) {
            return [];
        }
        return $result->toArray();
    }

    public function getAllEnableMenu(): array
    {
        $where[] = ['status', '=', '1'];
        $where[] = ['ismenu', '=', '1'];
        return Model::where($where)->get()->toArray();
    }

    public function getEnableRulesById($ids): array
    {
        $rules = Model::query()
            ->select(['id', 'pid', 'path', 'auth', 'icon', 'title', 'ismenu'])
            ->where('status', '=', '1');
        if (!in_array('*', $ids)) {
            $rules->whereIn('id', $ids);
        }
        return $rules->get()->toArray();
    }

    public function insertRule($data)
    {
        return Model::query()->insert($data);
    }

    public function updateRuleById($id, $data)
    {
        return Model::query()->where('id', $id)->update($data);
    }

    public function deleteRulesById($ids)
    {
        return Model::query()->whereIn('id', $ids)->delete();
    }
}