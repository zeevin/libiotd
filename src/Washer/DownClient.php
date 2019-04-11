<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-10
 * Time: 15:35
 * Source: DownClient.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Washer;


use Zeevin\Libiotd\Core\Exception\LibdException;
use Zeevin\Libiotd\Core\Protocols\Downstream;

/**
 * 洗衣机设备下行控制类
 * @link    https://www.init.lu
 * @author  Cao Kang(caokang@outlook.com)
 * Class DownClient
 * @package Zeevin\Libiotd\Washer
 */
class DownClient extends Downstream
{
    //设备是否可预约
    const CAN_RESERVATION = true;
    /** @var int 无定义 */
    const COMMAND_NAC = 0x01;
    /** @var int 开始工作 */
    const COMMAND_WORK = 0x02;
    /** @var int 预约洗衣机*/
    const COMMAND_RESERVE = 0x03;
    /** @var int 获取洗衣机当前状态 */
    const COMMAND_GET_STATUS = 0x04;
    /** @var int 取消当前预约 */
    const COMMAND_RESERVE_CANCEL = 0x05;
    /** @var int 控制设备等待接受投币信号 */
    const COMMAND_GET_COIN = 0x06;
    /** @var int 设置设备工作模式费率 */
    const COMMAND_SET_FEE = 0x07;


    /** @var int 加强洗 */
    const MODE_STRENGTHEN = 0x01;
    /** @var int 标准洗 */
    const MODE_STANDARD = 0x02;
    /** @var int 快速洗 */
    const MODE_QUICK = 0x03;
    /** @var int 单脱水 */
    const MODE_DRY = 0x04;
    /** @var int 洁桶 */
    const MODE_DISINFECTION = 0x05;
    /** @var int 自检 */
    const MODE_SELF_TEST = 0x06;

    /** @var int 待机中 */
    const WS_STANDBY = 0x00;
    /** @var int 预约中 */
    const WS_RESERVATION = 0x01;
    /** @var int 洁桶中 */
    const WS_DISINFECTION = 0x02;
    /** @var int 正常洗衣中 */
    const WS_WORKING = 0x03;
    /** @var int 洗衣结束 */
    const WS_DONE = 0x04;
    /** @var int 自检中 */
    const WS_SELF_TESTING = 0x05;
    /** @var int 注水超时 */
    const WS_WATER_INJECTION_TIMEOUT = 0x06;
    /** @var int 排水超时 */
    const WS_WATER_DRAIN_TIMEOUT = 0x07;
    /** @var int 脱水时撞桶 */
    const WS_HIT = 0x08;
    /** @var int 开盖报警 */
    const WS_COVER_OPEN = 0x09;
    /** @var int 水位传感器异常 */
    const WS_LEVEL_SENSOR_ERROR = 0x0A;
    /** @var int 溢水 */
    const WS_OVERFLOWING = 0x0B;
    /** @var int 电机故障 */
    const WS_MOTOR_ERROR = 0x0C;
    /** @var int 收到投币信号 */
    const WS_COIN_SIGNAL = 0xFE;
    /** @var int 通讯故障 */
    const WS_CONN_ERROR = 0x0D;

    /**
     * 预约洗衣机
     * @param string $imei
     *
     * @return DownClient
     * @throws LibdException
     */
    public function reserve(string $imei)
    {
        return $this->setImei($imei)->buildCommand(self::COMMAND_RESERVE,0x00,0x00)->send();
    }

    /**
     * 取消洗衣机预约
     * @param string $imei
     *
     * @return DownClient
     * @throws LibdException
     */
    public function cancelReserve(string $imei)
    {
        return $this->setImei($imei)->buildCommand(self::COMMAND_RESERVE_CANCEL,0x00,0x00)->send();
    }

    /**
     * 发起洗衣机工作指令
     * @param string $imei 设备imei
     * @param int    $mode 工作模式
     *
     * @return DownClient
     * @throws LibdException
     */
    public function start(string $imei,int $mode)
    {
        if (is_array($this->getMode($mode)))
            throw new LibdException('mode not exist');

        return $this->setImei($imei)->buildCommand(self::COMMAND_WORK,$mode,0x00)->send();
    }

    /**
     * 获取洗衣机支持的所有模式
     * @param null $m
     *
     * @return array|mixed
     */
    protected function getMode($m=null)
    {
        $modes = [
            self::MODE_STRENGTHEN=>'加强洗',
            self::MODE_STANDARD=>'标准洗',
            self::MODE_QUICK=>'快速洗',
            self::MODE_DRY=>'单脱水',
            self::MODE_DISINFECTION=>'洁桶',
            self::MODE_SELF_TEST=>'自检'
        ];

        return isset($m) && array_key_exists($m,$modes)?$modes[$m]:$modes;
    }
}