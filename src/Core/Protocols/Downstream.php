<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-08
 * Time: 17:03
 * Source: Downstream.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Core\Protocols;


use Zeevin\Libiotd\Core\Exception\LibdException;
use Zeevin\Libiotd\Core\Protocols\Traits\Utils;
use Zeevin\Libiotd\Core\ServiceContainer;

abstract class Downstream
{
    const CMD_HEAD = 0xAA;
    const CMD_END = 0x55;

    protected $imei;
    protected $command;
    protected $app;

    use Utils;

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * 强制关闭长连接
     */
    public function kickOff()
    {
        $this->command = 'bye';
        $this->send();
    }

    /**
     * @param string $imei
     *
     * @return $this
     */
    protected function setImei(string $imei)
    {
        $this->imei = $imei;
        return $this;
    }

    /**
     * 生成下行指令字符串
     *
     * @param int   $command
     * @param mixed ...$data
     *
     * @return $this
     */
    protected function buildCommand(int $command, ...$data)
    {
        array_unshift($data, $command);
        $length = count($data);
        array_unshift($data, $length);
        $crc16 = $this->crc16_generate($data);
        array_unshift($data, self::CMD_HEAD);
        array_walk(
            $data,
            function (&$v, $k) {
                $v = sprintf('%02x', $v);
            }
        );
        $this->command = implode('', $data).$crc16.'55';
        return $this;
    }

    /**
     * @return $this
     * @throws LibdException
     */
    protected function send()
    {
        if (!$this->imei)
            throw new LibdException('imei not set');
        $queue = $this->app['queue'];
        $queue->lpush($this->imei.'_out', $this->command);
        return $this;
    }
}