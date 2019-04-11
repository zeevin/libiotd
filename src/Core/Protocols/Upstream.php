<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-08
 * Time: 16:50
 * Source: Upstream.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Core\Protocols;

/**
 * Class Upstream
 *
 * @package Zeevin\Libiotd\Core\Protocols
 */
abstract class Upstream
{
    /** @var int 数据头 */
    const CMD_HEAD = 0xAA;
    /** @var int 数据尾 */
    const CMD_END = 0x55;
    /** @var int crc1正确 */
    const CRC1_SUCCESS = 0x06;
    /** @var int crc1失败需要重发上一条指令 */
    const CRC1_FAIL = 0x15;

    /** @var array 完整指令的hex数组 */
    public $_instruction = [];

    /**
     * @param string $instruction
     *
     * @return $this
     */
    public function init(string $instruction)
    {
        $bind_data = hex2bin($instruction);
        $bind_data_length = strlen($bind_data);
        for ($i = 0; $i < $bind_data_length; $i++) {
            $this->_instruction[] = hexdec(bin2hex($bind_data[$i]));
        }

        return $this;
    }

    /**
     * 获取指令头
     *
     * @return int
     */
    public function getHead(): int
    {
        return $this->_instruction[0];
    }

    /**
     * 获取指令尾
     * @return int
     */
    public function getEnd(): int
    {
        return $this->_instruction[9];
    }

    /**
     * 获取CRC，此处固定为0x06 或者 0x15 当前并未使用此字段参与逻辑处理
     * @return int
     */
    public function getCrc_1() :int
    {
        return $this->_instruction[1];
    }

    /**
     * 获取指令长度
     * @return int
     */
    public function getInstructionLength() :int
    {
        return $this->_instruction[2];
    }

    /**
     * 获取设备类型：实际业务中并未使用该字段
     * @return int
     */
    public function getDeviceType() :int
    {
        return $this->_instruction[3];
    }

    /**
     * 获取CRC校验位
     * @return string
     */
    public function getCrc_2()
    {
        return dechex($this->_instruction[7]).dechex($this->_instruction[8]);
    }
}