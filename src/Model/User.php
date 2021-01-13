<?php

declare(strict_types=1);

namespace Alexzy\HyperfAuth\Model;

use Alexzy\HyperfAuth\AuthInterface\UserModelInterface;
use Hyperf\DbConnection\Model\Model;

/**
 * Class User
 * @package Alexzy\HyperfAuth\Model
 */
class User extends Model implements UserModelInterface
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    public function getId()
    {
        return $this->getKey();
    }

    public function getUserById($id): ?UserModelInterface
    {
        return self::query()->find($id);
    }
}