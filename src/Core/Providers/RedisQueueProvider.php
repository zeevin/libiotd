<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-10
 * Time: 09:58
 * Source: RedisQueueProvider.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Core\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Predis\Client;

class RedisQueueProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $redisConfig = $app['config']->get('redis');

        $app['queue'] = function () use ($redisConfig)
        {
            $redis = new Client([
                'scheme' => 'tcp',
                'host' => $redisConfig['host'],
                'port' => $redisConfig['port'],
                'password' => $redisConfig['password'],
                'database' => $redisConfig['database']
            ]);

            return $redis;
        };
    }
}