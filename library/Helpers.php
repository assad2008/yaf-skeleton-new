<?php

/**
 * @Filename:  Helpers.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-07-18 11:00:45
 * @Synopsis:  自定义函数库
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-09 00:15:04
 */

use Yaf\Registry;

/**
 * belongsto Helpers.php
 * 组装数据写入Log文件
 *
 * @param      <type>  $url       The url
 * @param      array   $request   The request
 * @param      array   $response  The response
 *
 * @author     assad
 * @since      2020-02-28T00:20
 */
function wirteHttpBufData($url, $request = [], $response = []) {
    $logStr = '';
    $logStr .= 'REQUEST_URI:' . $url;
    $requestData = jsonEncode($request);
    $logStr .= ';REQUEST_DATA:' . $requestData;
    $responseData = jsonEncode($response);
    $logStr .= ';RESPONSE DATA:' . $responseData;
    sendLog($logStr);
}

/**
 * belongsto Helpers.php
 * 获得某月的开始结束时间戳
 *
 * @param      boolean|integer|string  $year   The year
 * @param      boolean|integer         $month  The month
 *
 * @return     array                   The month begin and end.
 *
 * @author     assad
 * @since      2020-05-12T11:52
 */
function getMonthBeginAndEnd($year = 0, $month = 0) {
    $year = $year ? $year : date('Y');
    $month = $month ? $month : date('m');
    $d = date('t', strtotime($year . '-' . $month));
    return ['begin' => strtotime($year . '-' . $month), 'end' => mktime(23, 59, 59, $month, $d, $year)];
}

/**
 * belongsto Helpers.php
 * 用时间戳获得当月的开始结束时间戳
 *
 * @param      boolean|integer  $timestamp  The timestamp
 *
 * @return     array            The month begin and end by timestamp.
 *
 * @author     assad
 * @since      2020-05-12T11:52
 */
function getMonthBeginAndEndByTimestamp($timestamp = 0) {
    $timestamp = $timestamp ? $timestamp : time();
    $year = date('Y', $timestamp);
    $month = date('m', $timestamp);
    $d = date('t', strtotime($year . '-' . $month));
    return [
        'begin' => strtotime($year . '-' . $month),
        'end' => mktime(23, 59, 59, $month, $d, $year),
    ];
}