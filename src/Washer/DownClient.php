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
use Zeevin\Libiotd\Core\Protocols\Contracts\DownstreamInterface;
use Zeevin\Libiotd\Core\Protocols\Downstream;

/**
 * 洗衣机设备下行控制类
 * Class DownClient
 *
 * @package Zeevin\Libiotd\Washer
 */
class DownClient extends Downstream implements DownstreamInterface
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


    /**
     * @param null $command
     *
     * @return mixed|null
     */
    public function expectedCommandMap($command = null)
    {
        /**
         * key:下行的命令，value上行的
         */
        $maps = [
            0x02 => 0x03,//下行工作指令，期望洗衣机上行工作中状态
            0x03 => 0x01,//下行预约指令，期望洗衣机上行预约中状态
            0x05 => 0x00,//下行取消预约指令，期望洗衣机上相待机中状态
        ];

        return array_key_exists($command,$maps)?$maps[$command]:null;
    }

    public function expectedModeMap($mode = null)
    {
        $maps = [
            0x05 => 0x02, //下行，期望获得消毒清清洁中运行状态
            0x06 => 0x05 //下行，期望获得洗衣机自检中运行状态
        ];
        return array_key_exists($mode,$maps)?$maps[$mode]:null;
    }

    /**
     * @return mixed
     */
    public function getExpect()
    {
        return $this->expect;
    }


    /**
     * 预约洗衣机
     * @param string $imei
     *
     * @return DownClient
     * @throws LibdException
     */
    public function reserve(string $imei)
    {
        $this->expect = $this->expectedCommandMap(self::COMMAND_RESERVE);
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
        $this->expect = $this->expectedCommandMap(self::COMMAND_RESERVE_CANCEL);
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

        $this->expect = $this->expectedModeMap($mode)??$this->expectedCommandMap(self::COMMAND_WORK);
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