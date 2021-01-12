<?php

declare(strict_types=1);

namespace Alexzy\HyperfAuth\Dao;

use Alexzy\HyperfAuth\AuthInterface\UserDaoInterface;
use Alexzy\HyperfAuth\Model\User as Model;

class User implements UserDaoInterface
{

    public function getAllUserIds(): array
    {
        return Model::query()->get()->pluck('id')->toArray();
    }
}