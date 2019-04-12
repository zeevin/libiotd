<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-10
 * Time: 16:50
 * Source: washer.php
 * Project: libiotd
 */

require '../vendor/autoload.php';
$config_array = require './config.php';
$app = new Zeevin\Libiotd\Application($config_array);

//print_r($app['config']->get('downstream')['queue_id_suffix']);

//下行命名测试

try {
    /** @var Zeevin\Libiotd\Washer\DownClient $washer_down */
    $washer_down = $app['washer.down'];
    $ret = $washer_down->cancelReserve('123456777');
    var_dump($ret->getExpect());

} catch (Zeevin\Libiotd\Core\Exception\LibdException $e) {

} catch (Exception $e) {

}

//上行命令解析测试

//try {
//    /** @var Zeevin\Libiotd\Washer\UpClient $washer_up */
//    $washer_up = $app['washer.up'];
//    $ret = $washer_up->init('AA060400032400695255');
//    print_r($ret->getAllData());
//    print_r($ret->getCoreData());
//
//} catch (Zeevin\Libiotd\Core\Exception\LibdException $e) {
//
//} catch (Exception $e) {
//
//}

