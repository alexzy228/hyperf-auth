<?php


namespace Alexzy\HyperfAuth\Service;


use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

class CacheService
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var CacheInterface|mixed
     */
    private $cache;

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get(ConfigInterface::class)->get('auth');
        $this->cache = $container->get(CacheInterface::class);
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function getConfig()
    {
        return $this->config;
    }

}