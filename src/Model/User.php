<?php

declare(strict_types=1);

namespace Alexzy\HyperfAuth\Model;

use Alexzy\HyperfAuth\AuthInterface\UserModelInterface;
use Hyperf\DbConnection\Model\Model;

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
}