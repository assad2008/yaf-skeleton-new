<?php

/**
 * @Filename:  RedisQueue.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-09-03 17:55:50
 * @Synopsis:  简单队列服务
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 13:01:42
 */

namespace Yservices;

class RedisQueue {

    public static $redisIns;

    /**
     * belongsto RedisQueue.php
     * 获得redis实例
     *
     * @return     object  The redis instance.
     *
     * @author     assad
     * @since      2019-09-04T10:45
     */
    public static function getRedisInstance() {
        $redisConfig = \Yaf\Registry::get('di')['config']['redis'];
        if (is_null(self::$redisIns)) {
            self::$redisIns = new \Predis\Client([
                'scheme' => 'tcp',
                'host' => $redisConfig['host'],
                'port' => $redisConfig['port'],
            ]);
        }
        return self::$redisIns;
    }

    /**
     * belongsto RedisQueue.php
     * 获得队列名 拼装当前开发模式，防止数据冲突
     *
     * @param      string  $queueName  The queue name
     *
     * @return     string  The queue name.
     *
     * @author     assad
     * @since      2019-09-04T10:46
     */
    public static function getQueueName($queueName) {
        return $queueName . '_' . APP_ENV;
    }

    /**
     * belongsto RedisQueue.php
     * 进队
     *
     * @param      string   $queueName       队列名
     * @param      string|array   $val       进队数据
     *
     * @return     boolean  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-09-04T10:46
     */
    public static function in($queueName, $val) {
        if (!$queueName || !$val) {
            return false;
        }
        $redis = self::getRedisInstance();
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $redis->lPush(self::getQueueName($queueName), $val);
    }

    /**
     * belongsto RedisQueue.php
     * 出对
     *
     * @param      string   $queueName  队列名
     *
     * @return     boolean|string  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-09-04T10:47
     */
    public static function out($queueName) {
        if (!$queueName) {
            return false;
        }
        $redis = self::getRedisInstance();
        $data = $redis->rPop(self::getQueueName($queueName));
        return $data;
    }

}