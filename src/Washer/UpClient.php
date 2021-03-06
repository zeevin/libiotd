<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-10
 * Time: 17:57
 * Source: UpClient.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Washer;


use Zeevin\Libiotd\Core\Protocols\Upstream;

class UpClient extends Upstream
{
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
     * 获取洗衣机工作状态值
     * @return int
     * @throws \Zeevin\Libiotd\Core\Exception\LibdException
     */
    public function getWorkStatus() :int
    {
        $data = $this->getCoreData();
        return $data[1];
    }

    /**
     * 获取运行时间倒计时值，单位为分钟
     * @return int
     * @throws \Zeevin\Libiotd\Core\Exception\LibdException
     */
    public function getRemainingTime() :int
    {
        $data = $this->getCoreData();
        return $data[2];
    }
}