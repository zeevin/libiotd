<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-08
 * Time: 17:16
 * Source: Utils.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Core\Protocols\Traits;


trait Utils
{
    /**
     * 生成crc16校验位
     * @param array $data 16进制组成的数据如：[0x06,0x04,0x00,0x00,0xff,0x77]
     *
     * @return string 返回iot设备定义校验规则的校验结果，16进制形式(去掉前面的0x)，如：3a9d
     */
    protected function crc16_generate(array $data)
    {
        $h = 0xffff;
        foreach ($data as $value) {
            $unsigned_value = sprintf('%u', $value);
            $h ^= $unsigned_value;
            for ($i = 0; $i < 8; $i++) {
                $lsb = $h & 0x0001;
                $h >>= 1;
                if ($lsb == 1) {
                    $h ^= 0x8408;
                }
            }
        }
        $h ^= 0xffff;
        return str_pad(dechex($h), 4, 0, STR_PAD_LEFT);
    }

}