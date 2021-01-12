<?php

namespace Alexzy\HyperfAuth\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * Class Auth
 * @package Alexzy\HyperfAuth\Annotation
 * @Annotation
 * @Target({"METHOD", "CLASS"})
 */
class Auth extends AbstractAnnotation
{
    /**
     * @var bool
     */
    public $noNeedLogin = false;

    /**
     * @var bool
     */
    public $noNeedRight = false;

    public function __construct($value = null)
    {
        parent::__construct($value);
    }

}