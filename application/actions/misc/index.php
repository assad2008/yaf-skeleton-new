<?php

/**
 * @Filename:  index.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2020-07-08 12:36:24
 * @Synopsis:
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 12:36:39
 */

use Ycommon\ActionBase;

final class indexAction extends ActionBase {

    public function _before() {
        $this->tpl->assign('page_active', 'misc');
    }

    public function _exe() {
        $this->tpl->display('index.html');
    }
}