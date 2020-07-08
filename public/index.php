<?php

/**
 * @Filename:  index.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-06-29 15:05:10
 * @Synopsis:  文件入口
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 12:58:00
 */

use Yaf\Application;
use Yaf\Registry;

define('SYS_START_TIME', microtime());
Registry::set("starttime", SYS_START_TIME);

define('TIMESTAMP', time());

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/application');
define('LIB_PATH', ROOT_PATH . '/library');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATA_PATH', ROOT_PATH . '/data');

define('APP_ENV', \YAF\ENVIRON);

$application = new Application(CONFIG_PATH . "/application.ini");
$application->bootstrap()->run();