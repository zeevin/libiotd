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

/**
 * 下行指令基类
 * Class Downstream
 *
 * @package Zeevin\Libiotd\Core\Protocols
 */
abstract class Downstream
{
    const CMD_HEAD = 0xAA;
    const CMD_END = 0x55;
    /**
     * @var string
     */
    protected $imei;
    /**
     * @var integer
     */
    protected $command;
    protected $app;
    /**
     * @var null|integer 最后一次命令下发后期望的上行值
     */
    protected $expect;

    use Utils;

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * 强制关闭长连接，使设备掉线
     * @throws LibdException
     */
    public function kickOff()
    {
        if (!$this->imei)
            throw new LibdException('imei not set');
        $this->command = 'bye';
        $this->send();
    }

    /**
     * 设置发送对象imei
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
     * @param int   $command
     * @param mixed ...$data
     *
     * @return $this
     * @throws LibdException
     */
    protected function buildCommand(int $command, ...$data)
    {
        if (!$this->imei)
            throw new LibdException('imei not set');

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
     * 写入下行队列，完成命令发送
     * @return $this
     * @throws LibdException
     */
    protected function send()
    {
        if (!$this->command)
            throw new LibdException('command not set');
        $queue = $this->app['queue'];
        $queue->lpush($this->imei.$this->app['config']->get('downstream')['queue_id_suffix'], $this->command);
        return $this;
    }
}