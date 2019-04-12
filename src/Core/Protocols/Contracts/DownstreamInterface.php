<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-11
 * Time: 11:34
 * Source: DownstreamInterface.php
 * Project: libiotd
 */

namespace Zeevin\Libiotd\Core\Protocols\Contracts;


interface DownstreamInterface
{
    /**
     * 下行命令与上行运行状态期望映射关系
     * @param null $command
     *
     * @return mixed
     */
    public function expectedCommandMap($command = null);

    /**
     * 下行工作命令时，某个工作模式与上行运行状态的期望映射关系
     * @param null $mode
     *
     * @return mixed
     */
    public function expectedModeMap($mode = null);

    /**
     * 获取本次指令下发后期望设备上相的工作状态
     *
     * @return mixed
     */
    public function getExpect();
}