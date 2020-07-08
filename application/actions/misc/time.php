<?php

/**
 * @Filename:  time.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2020-07-08 12:37:12
 * @Synopsis:  服务器时间
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 12:59:28
 */

use Ycommon\ActionBase;

final class timeAction extends ActionBase {

    public function _before() {
    }

    public function _exe() {
        $this->json([
            'timestamp' => TIMESTAMP,
            'time' => date('Y-m-d H:i:s', TIMESTAMP),
        ]);
    }
}