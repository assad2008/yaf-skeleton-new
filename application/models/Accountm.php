<?php

/**
 * @Filename:  Accountm.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2020-02-13 19:22:44
 * @Synopsis:  账号表
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-08 13:09:31
 */

namespace Ymodels;

class Accountm extends BaseModel {

    protected $connection = 'mdb';
    protected $table = 'yee_accounts';
    protected $primaryKey = "id";
    public $timestamps = false;

    /**
    `id` int(11) NOT NULL,
    `account_name` varchar(50) DEFAULT NULL COMMENT '用户名',
    `nickname` varchar(80) DEFAULT NULL COMMENT '昵称',
    `password` varchar(80) DEFAULT NULL COMMENT '密码',
    `avatar` varchar(150) DEFAULT NULL COMMENT '头像',
    `gender` char(2) DEFAULT 'n' COMMENT 'm男 f女 n未知',
    `mobile_phone` char(18) DEFAULT NULL COMMENT '手机号码',
    `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
    `signature_txt` varchar(500) DEFAULT NULL COMMENT '签名',
    `reg_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '注册类型 1正常注册 2facebook登录',
    `thrid_id` char(150) DEFAULT 'none' COMMENT '第三方ID',
    `reg_ip` char(20) DEFAULT NULL COMMENT '注册IP',
    `last_login_time` bigint(20) DEFAULT '0' COMMENT '最后登录时间',
    `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '状态 1 禁止 2正常',
    `delete_status` tinyint(1) DEFAULT '2' COMMENT '删除状态 1删除 2未删除',
    `created_at` bigint(20) DEFAULT '0',
    `updated_at` bigint(20) DEFAULT '0',
    `password_change_time` int(11) DEFAULT '0' COMMENT '密码修改时间'
     */

    /**
     * belongsto Accountm.php
     * 注册账户
     *
     * @param      string   $accountName  账户名
     * @param      string   $password     密码
     *
     * @return     boolean  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2020-05-10T14:33
     */
    public static function register($accountName, $password) {
        $account = [];
        $account['account_name'] = $accountName;
        $account['nickname'] = 'yee_' . random(6);
        $account['password'] = password_hash($password, PASSWORD_BCRYPT);
        $account['reg_type'] = 1;
        $account['reg_ip'] = ip();
        $account['created_at'] = time();
        $accountId = self::insertGetId($account);
        return $accountId ?: false;
    }

    /**
     * belongsto Accountm.php
     * 检查用户是否存在
     *
     * @param      <type>  $accountName  The account name
     *
     * @return     <type>  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2020-05-17T09:02
     */
    public static function checkNameExist($accountName) {
        return self::oneByWhere(['account_name' => $accountName]);
    }

    /**
     * belongsto Accountm.php
     * 检查昵称
     *
     * @param      <type>  $nickname  The nickname
     * @param      <type>  $id        The identifier
     *
     * @author     assad
     * @since      2020-05-17T09:04
     */
    public static function checkNickName($nickname, $id) {
        $exist = self::where('id', '!=', $id)->where('nickname', $nickname)->first();
        return $exist ?: false;
    }

    /**
     * belongsto Accountm.php
     * 检查手机号码是否存在
     *
     * @param      <type>  $phone  The phone
     * @param      <type>  $id     The identifier
     *
     * @author     assad
     * @since      2020-05-17T17:33
     */
    public static function checkPhoneExist($phone) {
        $exist = self::where('mobile_phone', $phone)->first();
        return $exist ?: false;
    }

    /**
     * belongsto Accountm.php
     * 检查邮箱是否存在
     *
     * @param      <type>   $email  The email
     *
     * @return     boolean  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2020-05-17T17:35
     */
    public static function checkEmailExist($email) {
        $exist = self::where('email', $email)->first();
        return $exist ?: false;
    }

}