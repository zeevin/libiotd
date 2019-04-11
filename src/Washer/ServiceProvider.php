<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-10
 * Time: 15:35
 * Source: ServiceProvider.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Washer;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['washer.down'] = function ($app)
        {
            return new DownClient($app);
        };
    }
}