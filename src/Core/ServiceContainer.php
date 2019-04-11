<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-10
 * Time: 10:01
 * Source: ServiceContainer.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Core;


use Pimple\Container;

class ServiceContainer extends Container
{
    protected $providers = [];
    protected $defaultConfig = [];
    protected $globalConfig = [];

    public function __construct(array $config, array $prepends = [])
    {
        parent::__construct($prepends);
        $this->registerConfig($config)
            ->registerProviders();
    }

    /**
     * @return $this
     */
    protected function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider);
        }

        return $this;
    }

    /**
     * 注册配置文件
     *
     * @param array $config
     *
     * @return $this
     */
    protected function registerConfig(array $config)
    {
        $this['config'] = function () use ($config) {
            return new Config(
                array_replace_recursive(
                    $this->globalConfig,
                    $this->defaultConfig,
                    $config
                )
            );
        };

        return $this;
    }
}