<?php

/**
 * @Filename:  index.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2020-07-08 00:21:09
 * @Synopsis:
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-09 00:17:23
 */

use Ycommon\ActionBase;
use Ymodels\Accountm;

final class indexAction extends ActionBase {

    public function _before() {
        $this->tpl->page_title = 'index';
    }

    public function _exe() {
        $account = Accountm::getRow(['account_name' => 'wangjiang']);
        $this->tpl->account = $account;
        $this->tpl->display('index.html');
    }
}