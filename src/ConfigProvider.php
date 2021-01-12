<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Ycbl\AdminAuth;

use Alexzy\HyperfAuth\AuthInterface\AuthGroupAccessDaoInterface;
use Alexzy\HyperfAuth\AuthInterface\AuthGroupDaoInterface;
use Alexzy\HyperfAuth\AuthInterface\AuthRuleDaoInterface;
use Alexzy\HyperfAuth\AuthInterface\LoginGuardInterface;
use Alexzy\HyperfAuth\AuthInterface\UserDaoInterface;
use Alexzy\HyperfAuth\Dao\AuthGroup;
use Alexzy\HyperfAuth\Dao\AuthRule;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Filesystem\Filesystem;
use Ycbl\AdminAuth\Dao\AuthGroupAccess;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                LoginGuardInterface::class => '',
                AuthGroupAccessDaoInterface::class => AuthGroupAccess::class,
                AuthGroupDaoInterface::class => AuthGroup::class,
                AuthRuleDaoInterface::class => AuthRule::class,
                UserDaoInterface::class => '',
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'admin_auth 组件配置.', // 描述
                    // 建议默认配置放在 publish 文件夹中，文件命名和组件名称相同
                    'source' => __DIR__ . '/../publish/config/admin_auth.php',  // 对应的配置文件路径
                    'destination' => BASE_PATH . '/config/autoload/admin_auth.php', // 复制为这个路径下的该文件
                ],
            ],
        ];
    }

    protected function getMigrationFileName(): string
    {
        $timestamp = date('Y_m_d_His');
        $filesystem = new Filesystem();
        return Collection::make(BASE_PATH . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path . '*_create_auth_tables.php');
            })->push(BASE_PATH . "/migrations/{$timestamp}_create_auth_tables.php")
            ->first();
    }
}
