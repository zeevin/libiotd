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

use Zeevin\Libiotd\Core\Exception\LibdException;
use Zeevin\Libiotd\Core\ServiceContainer;

/**
 * 上行指令解析基类
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

    protected $app;
    protected $dataLength;

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    /**
     * 将上行字符串指令转换成十六进制数组如：[0xaa,0x01,0x02,0x00,0x00,0x55]
     * @param string $instruction
     *
     * @return $this
     */
    public function init(string $instruction) :self
    {
        $bind_data = hex2bin($instruction);
        $bind_data_length = strlen($bind_data);
        for ($i = 0; $i < $bind_data_length; $i++) {
            $this->_instruction[] = hexdec(bin2hex($bind_data[$i]));
        }
        $this->dataLength = count($this->_instruction);
        return $this;
    }

    /**
     * 获取指令头
     *
     * @return int
     * @throws LibdException
     */
    public function getHead(): int
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');
        return $this->_instruction[0];
    }

    /**
     * 获取数据总字节数
     * @return int
     * @throws LibdException
     */
    public function getAllLength() :int
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');
        return $this->dataLength;
    }

    /**
     * 获取指令尾
     * @return int
     * @throws LibdException
     */
    public function getEnd(): int
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');
        return $this->_instruction[$this->getAllLength()-1];
    }

    /**
     * 获取CRC，此处固定为0x06 或者 0x15 当前并未使用此字段参与逻辑处理
     * @return int
     * @throws LibdException
     */
    public function getCrc_1() :int
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');
        return $this->_instruction[1];
    }

    /**
     * 获取指令长度
     * @return int
     * @throws LibdException
     */
    public function getInstructionLength() :int
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');
        return $this->_instruction[2];
    }

    /**
     * 获取设备类型：实际业务中并未使用该字段
     * @return int
     * @throws LibdException
     */
    public function getDeviceType() :int
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');
        return $this->_instruction[3];
    }

    /**
     * 获取CRC校验位字节数组
     * @return array
     * @throws LibdException
     */
    public function getCrc_2() :array
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');
        return array_slice($this->_instruction,-3,2);
    }

    /**
     * 获取指令核心数据字节数组
     * @return array
     * @throws LibdException
     */
    public function getCoreData() :array
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');
        return array_slice($this->_instruction,3,$this->getInstructionLength());
    }

    /**
     * 获取整个上行指令的字节数组
     * @return array
     * @throws LibdException
     */
    public function getAllData() :array
    {
        if (empty($this->_instruction))
            throw new LibdException('instruction not set');

        return $this->_instruction;
    }
}