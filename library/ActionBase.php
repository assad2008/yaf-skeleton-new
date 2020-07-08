<?php

/**
 * @Filename:  ActionBase.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-07-18 11:00:45
 * @Synopsis:  Action基类
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-09 00:52:42
 */

namespace Ycommon;

use Yaf\Action_Abstract;
use Yaf\Registry;

/**
 * belongsto ActionBase.php
 * Action基类
 *
 * @author     assad
 * @since      2020-07-09T16:10
 */
class ActionBase extends Action_Abstract {

    public $config = [];
    public $input;
    public $tpl;
    public $di;
    public $logger;
    public $cache;
    public $session;
    public $baseUrl = '';
    public $accountId = 0;
    public $accountInfo = [];
    public $moduleName = '';
    public $currentAction = '';

    public function _init() {
        $this->config = Registry::get('config');
        $this->di = Registry::get('di');
        $this->logger = $this->di['log'];
        $this->cache = $this->di['cache'];
        $this->session = $this->di['session'];
        $this->input = $this->getRequest();
        $this->_view();
        $this->_initParams();
        $this->_loginStatus();
        $this->_formHash();
    }

    public function _before() {}

    public function _after() {}

    /**
     * belongsto ActionBase.php
     * Action实际执行函数
     *
     * @author     assad
     * @since      2020-07-09T16:11
     */
    final public function execute() {
        $this->_init();
        try {
            $this->_before();
            $this->_exe();
            $this->_after();
        } catch (Exception $e) {
            debug($e);
            exit(0);
        }
    }

    /**
     * belongsto ActionBase.php
     * 初始化参数
     *
     * @author     assad
     * @since      2020-02-24T23:01
     */
    private function _initParams() {
        $this->moduleName = $this->getController()->moduleName;
        $this->currentAction = strtolower($this->getController()->currentAction);
        $this->baseUrl = $this->config['application']['baseUrl'] . '/';
        $this->tpl->base_url = $this->baseUrl;
        $this->tpl->assets_url = $this->config['application']['assetsUrl'];
    }

    /**
     * belongsto ActionBase.php
     * 加载视图实例
     *
     * @author     assad
     * @since      2020-07-09T16:14
     */
    private function _view() {
        $view_config = $this->config['application']['view'];
        $options = [
            'cache' => $view_config['cachedir'],
            'debug' => false,
            'charset' => 'UTF-8',
            'auto_reload' => true,
        ];
        $this->tpl = new ViewBase($view_config['dir'], $options);
    }

    /**
     * belongsto ActionBase.php
     * 登录状态获取
     *
     * @author     assad
     * @since      2020-02-24T23:01
     */
    private function _loginStatus() {
        $this->accountId = $this->session->get('account_id') ?: 0;
        if ($this->accountId) {
            $cacheKey = \AppConstants::ACCOUNT_CACHE_KEY . $this->accountId;
            $accountInfo = [];
            $accountInfoFromCache = $this->cache->fetch($cacheKey);
            if (!$accountInfoFromCache) {
                $accountInfo = Accountm::one($this->accountId);
                if (!$accountInfo) {
                    jump($this->baseUrl . '/logout');
                }
                if ($accountInfo['status'] == 1) {
                    jump($this->baseUrl . '/logout');
                }
                if ($accountInfo) {
                    $this->cache->save($cacheKey, $accountInfo, 3600);
                }
            } else {
                $accountInfo = $accountInfoFromCache;
            }
            if ($accountInfo) {
                $this->accountInfo = $accountInfo;
                Registry::set(\AppConstants::REG_ACCOUNT_INFO . $this->accountId, $accountInfo);
            }
        }
        $this->tpl->assign('account', $this->accountInfo);
        $this->tpl->assign('account_id', $this->accountId);
    }

    /**
     * belongsto ActionBase.php
     * 检查登录状态
     *
     * @return     boolean  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2020-02-24T23:02
     */
    public function checkLogin($isJump = true) {
        //无需登录也无需跳转
        $noNeedLoginedActionsWithoutJump = [
            'index/index',
            'signout/index',
        ];

        if (in_array($this->currentAction, $noNeedLoginedActionsWithoutJump)) {
            return true;
        }

        //无需验证登录，但是已登录需要跳转
        $noNeedLoginedActions = [
            'signin/index',
            'signup/index',
            'findpasswd/index',
        ];
        if (in_array($this->currentAction, $noNeedLoginedActions)) {
            if ($this->accountId) {
                if ($isJump) {
                    jump($this->baseUrl . 'account');
                }
            }
        } else {
            if (!$this->accountId) {
                if ($isJump) {
                    jump($this->baseUrl . 'signin');
                } else {
                    $this->json([], -99, '需要登录');
                }
            }
        }
    }

    /**
     * belongsto ActionBase.php
     * 检查签名
     *
     * @author     assad
     * @since      2019-08-17T16:29
     */
    public function checkSign() {
        $isCheckSign = $this->get('mode', 'prod');
        if ($isCheckSign == 'dev') {
            return;
        }
        $unCheckSignUri = [
            '/index/index',
            '/upload/upload',
        ];
        if (in_array($this->uri, $unCheckSignUri)) {
            return;
        }
        $getParams = $this->get();
        $signKey = $this->config['signKey'];
        $sign = $getParams['sign'];
        unset($getParams['sign']);
        ksort($getParams);
        $signArray = [];
        foreach ($getParams as $key => $val) {
            $signArray[] = urlencode4js($key) . '=' . urlencode4js($val);
        }
        $signPerString = implode('&', $signArray);
        $signString = md5($signPerString . $signKey);
        if ($sign != $signString) {
            $this->json([], -99, '签名错误');
        }
    }

    /**
     * 生成form hash
     */
    private function _formHash() {
        if ($this->method() != 'POST') {
            $formHash = random(12);
            $this->tpl->form_hash = $formHash;
            $this->session->set('form_hash', $formHash);
        }
    }

    /**
     * 检查提交是否正确
     *
     * @param      boolean  $isCheck  Indicates if check
     *
     * @return     boolean  ( description_of_the_return_value )
     */
    public function checkSubmit($isCheck = true) {
        if ($this->method() != 'POST') {
            return false;
        }
        if ($isCheck) {
            $formHash = $this->post('form_hash');
            if (!$formHash) {
                return false;
            }
            $saveFromHash = $this->session->get('form_hash');
            if (!$saveFromHash) {
                return false;
            }
            if ($formHash != $saveFromHash) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * belongsto ActionBase.php
     * 得到POST参数
     *
     * @param      string  $key         The parameter
     * @param      mix     $defaultValue  The default value
     *
     * @return     mix     ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:11
     */
    public function post($key = null, $defaultValue = null) {
        if ($key === null) {
            return $this->input->getPost();
        }
        return $this->input->getPost($key, $defaultValue);
    }

    /**
     * belongsto ActionBase.php
     * 得到GET参数
     *
     * @param      string  $key         The parameter
     * @param      mix     $defaultValue  The default value
     *
     * @return     mix     ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:11
     */
    public function get($key = null, $defaultValue = null) {
        if ($key === null) {
            return $_REQUEST;
        }
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $defaultValue;
    }

    /**
     * belongsto ActionBase.php
     * 得到cookie参数
     *
     * @param      string  $key         The parameter
     * @param      mix     $defaultValue  The default value
     *
     * @return     mix     ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:12
     */
    public function cookie($key = null, $defaultValue = null) {
        if ($key === null) {
            return $this->request->getCookie();
        }
        return $this->request->getCookie($key, $defaultValue);
    }

    /**
     * belongsto ActionBase.php
     * 得到服务器相关参数
     *
     * @param      string  $key         The parameter
     * @param      string  $defaultValue  The default value
     *
     * @return     string  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:12
     */
    public function server($key = null, $defaultValue = null) {
        if ($key === null) {
            return $this->request->getServer();
        }

        return $this->request->getServer($key, $defaultValue);
    }

    /**
     * belongsto ActionBase.php
     * 请求方法，GET,POST,PUT
     *
     * @return     Function  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:13
     */
    public function method() {
        return $this->request->getMethod();
    }

    /**
     * belongsto ActionBase.php
     * 是否为ajax请求
     *
     * @return     boolean  True if ajax, False otherwise.
     *
     * @author     assad
     * @since      2019-07-09T16:13
     */
    public function isAjax() {
        return $this->request->isXmlHttpRequest();
    }

    /**
     * belongsto ActionBase.php
     * 返回json数据
     *
     * @param      array    $data   The data
     * @param      integer  $code   The code
     * @param      string   $tip    The tip
     *
     * @author     assad
     * @since      2019-07-09T16:14
     */
    public function json($data = [], $code = 0, $tip = 'success') {
        header('Content-type: application/json; charset=utf-8');
        $responseData = getResponseData($data, $tip, $code);
        wirteHttpBufData($this->currentAction, $this->get(), $responseData);
        $responseJsonData = jsonEncode($responseData);
        echo $responseJsonData;
        exit(0);
    }

    /**
     * belongsto ActionBase.php
     * 返回json数据，jsonp方式
     *
     * @param      array    $data   The data
     * @param      string   $tip    The tip
     * @param      integer  $code   The code
     *
     * @author     assad
     * @since      2019-07-09T16:14
     */
    public function jsonp($data = [], $tip = 'success', $code = 0) {
        $callback = $this->get('callback');
        if (!$callback) {
            exit("callback param is miss");
        }
        header('Content-type: application/json; charset=utf-8');
        $responseData = getResponseData($data, $tip, $code);
        wirteHttpBufData($this->uri, $this->params(), $responseData);
        $responseJosnData = jsonEncode($responseData);
        echo $callback . '(' . $responseJosnData . ')';
        exit(0);
    }

}