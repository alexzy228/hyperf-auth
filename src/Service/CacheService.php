<?php


namespace Alexzy\HyperfAuth\Service;


use Hyperf\Cache\Driver\DriverInterface;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class CacheService
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var DriverInterface|mixed
     */
    private $cache;

    public function __construct(ContainerInterface $container)
    {
        $this->config = $container->get(ConfigInterface::class)->get('auth');
        $this->cache = $container->get(DriverInterface::class);
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