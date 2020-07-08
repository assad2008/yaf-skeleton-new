<?php

/**
 * @Filename:  ControllerBase.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2020-07-08 11:18:32
 * @Synopsis:  控制器基类
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 11:30:16
 */

namespace Ycommon;

use Yaf\Controller_Abstract as Controller_Abstract;

/**
 * belongsto ControllerBase.php
 * 控制器基类
 *
 * @author     assad
 * @since      2019-07-09T16:09
 */
abstract class ControllerBase extends Controller_Abstract {

    public $moduleName = '';
    public $currentAction = '';

    /**
     * belongsto ControllerBase.php
     * { function_description }
     *
     * @author     assad
     * @since      2019-07-09T16:06
     */
    public function init() {
        $this->getBaseParams();
    }

    /**
     * belongsto ControllerBase.php
     * 初始化基本参数
     *
     * @author     assad
     * @since      2019-07-09T16:07
     */
    public function getBaseParams() {
        $moduleName = $this->getRequest()->module;
        $controller = $this->getRequest()->controller;
        $action = $this->getRequest()->action;

        $this->moduleName = $moduleName;
        $this->currentAction = $controller . '/' . $action;
    }
}
