<?php
/**
 * @link   https://www.init.lu
 * @author Cao Kang(caokang@outlook.com)
 * Date: 2019-04-08
 * Time: 12:03
 * Source: config.php
 * Project: libiotd
 */

return [
    'upstream'=>[
        'queue_id'=>'Squeue'
    ],
    'downstream'=>[
        'queue_id_suffix'=>'_out'
    ],
    'redis' => [
        'driver' => 'predis',
        'host' => '127.0.0.1',
        'password' => null,
        'port' => 6379,
        'database'=> 0
    ]
];