<?php

/**
 * @Filename:  Misc.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2020-07-08 12:35:53
 * @Synopsis:  杂项
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 12:37:06
 */

use Ycommon\ControllerBase;

final class MiscController extends ControllerBase {

    public $actions = [
        'index' => 'actions/misc/index.php',
        'time' => 'actions/misc/time.php',
    ];

}
