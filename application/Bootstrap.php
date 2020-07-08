<?php

/**
 * @Filename:  Bootstrap.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2020-03-01 11:01:26
 * @Synopsis:  Bootstrap启动文件
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 16:54:23
 */

use Illuminate\Database\Capsule\Manager as DBManager;
use Pimple\Container;
use Tracy\Debugger;
use Yaf\Application;
use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
use Yaf\Loader;
use Yaf\Registry;

class Bootstrap extends Bootstrap_Abstract {

    public $config;
    protected $loader;

    /**
     * belongsto Bootstrap.php
     * 初始化配置文件
     *
     * @author     assad
     * @since      2020-05-24T14:58
     */
    public function _initConfig() {
        $this->config = Application::app()->getConfig()->toArray();
        Registry::set('config', $this->config);
        $this->loader = Loader::getInstance();
    }

    /**
     * belongsto Bootstrap.php
     * 重命名session名字
     *
     * @author     assad
     * @since      2020-05-24T14:59
     */
    public function _initSessionName() {
        session_name('yee_sess_id');
    }

    /**
     * belongsto Bootstrap.php
     * 加载composer文件
     *
     * @author     assad
     * @since      2020-05-24T14:58
     */
    public function _initComposerAutoLoader() {
        $autoload = ROOT_PATH . '/vendor/autoload.php';
        if (file_exists($autoload)) {
            $this->loader->import($autoload);
        }
    }

    /**
     * belongsto Bootstrap.php
     * 设置调试级别
     *
     * @author     assad
     * @since      2020-05-24T14:58
     */
    public function _initDebugger() {
        /**
         *dev DEVELOPMENT  测试环境
         *prod PRODUCTION  正式环境
         */
    }

    /**
     * belongsto Bootstrap.php
     * 加载单独需要的文件
     *
     * @author     assad
     * @since      2020-05-24T14:59
     */
    public function _initLoadHelperFunction() {
        $this->loader->import(LIB_PATH . '/Helpers.php');
        $this->loader->import(LIB_PATH . '/Constants.php');
    }

    /**
     * belongsto Bootstrap.php
     * 加载service
     *
     * @author     assad
     * @since      2020-05-24T14:59
     */
    public function _initServices() {
        $services = [];
        $service_file = APP_PATH . '/Services.php';
        if (file_exists($service_file) && is_readable($service_file)) {
            $services = require $service_file;
        }
        $container = new Container($services);
        Registry::set('di', $container);
    }

    /**
     * belongsto Bootstrap.php
     * 加载数据库
     *
     * @author     assad
     * @since      2020-05-24T15:00
     */
    public function _initDbAdapter() {
        $capsule = new DBManager();
        $db = $this->config['database'];
        foreach ($db as $key => $value) {
            $capsule->addConnection($value, $key);
        }
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        class_alias("\Illuminate\Database\Capsule\Manager", "DB");
    }

    /**
     * belongsto Bootstrap.php
     * 禁用视图
     *
     * @param      \Yaf\Dispatcher  $dispatcher  The dispatcher
     *
     * @author     assad
     * @since      2020-05-24T15:00
     */
    public function _initView(Dispatcher $dispatcher) {
        $dispatcher->autoRender(false);
    }

}
