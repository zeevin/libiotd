<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-10
 * Time: 10:31
 * Source: Application.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd;


use Zeevin\Libiotd\Core\Providers\RedisQueueProvider;
use Zeevin\Libiotd\Core\ServiceContainer;
use Zeevin\Libiotd\Washer\ServiceProvider;

class Application extends ServiceContainer
{
    protected $providers = [
        RedisQueueProvider::class,
        ServiceProvider::class
    ];
}