<?php

/**
 * @Filename:  Index.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-07-18 11:00:45
 * @Synopsis:  https://
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 00:17:12
 */

use Ycommon\ControllerBase;

final class IndexController extends ControllerBase {

    public $actions = [
        'index' => 'actions/index/index.php',
    ];

}
