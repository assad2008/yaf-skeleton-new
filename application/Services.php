<?php

/**
 * @Filename:  Services.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-07-18 11:00:45
 * @Synopsis:  公共服务
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-06-08 15:32:42
 */

use Doctrine\Common\Cache\RedisCache;
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Yaf\Registry;
use Yaf\Session;

return [
    'config' => function ($c) {
        return Registry::get('config');
    },

    'session' => function ($c) {
        $session = Session::getInstance();
        $session->start();
        return $session;
    },

    'log' => function ($c) {
        $logger = new Logger($c["config"]["log"]["name"]);
        $log_filename = $c["config"]["log"]["filedir"] . date("Ymd") . ".log";
        $logger->pushHandler(new StreamHandler($log_filename));
        $logger->pushHandler(new FirePHPHandler());
        return $logger;
    },

    'cache' => function ($c) {
        /*
        缓存操作
        cache->fetch('key')
        cache->save('key',data,86400)
        cache->delete(key)
         */
        $redis = new redis();
        $redis->pconnect($c['config']['redis']['host'], $c['config']['redis']['port']);
        $cache = new RedisCache();
        $cache->setRedis($redis);
        Registry::set('cache', $cache);
        return $cache;
    },
];
