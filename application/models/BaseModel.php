<?php

/**
 * @Filename:  BaseModel.php
 * @Author:  assad
 * @Email:  rlk002@gmail.com
 * @Date:  2019-08-19 15:54:14
 * @Synopsis:  Model基类
 * @Version:  1.0
 * @Last Modified by:   assad
 * @Last Modified time: 2020-07-09 00:57:12
 */

namespace Ymodels;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Yaf\Registry;

/**
 * belongsto BaseModel.php
 * model基类
 *
 * @author     assad
 * @since      2019-07-09T16:07
 */
class BaseModel extends EloquentModel {

    /**
     * belongsto BaseModel.php
     * 得到主键
     *
     * @return     string
     *
     * @author     assad
     * @since      2019-07-09T16:08
     */
    protected static function KeyName() {
        return (new static )->getKeyName();
    }

    /**
     * belongsto BaseModel.php
     * 获得到表名
     *
     * @return     string
     *
     * @author     assad
     * @since      2019-07-09T16:08
     */
    protected static function TableName() {
        return (new static )->getTable();
    }

    /**
     * belongsto BaseModel.php
     * 根据主键获得一条数据
     *
     * @param      integer   $id     The identifier
     * @param      integer  $type   The type  1数组 2对象
     *
     * @return     array    ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-08-19T10:01
     */
    public static function one($id, $type = 1) {
        $res = self::find($id);
        if (!$res) {
            return [];
        }
        if ($type == 1) {
            return $res->toArray();
        } else {
            return $res;
        }
    }

    /**
     * belongsto BaseModel.php
     * 根据条件获取一条数据
     *
     * @param      array    $where  条件
     * @param      integer  $type   返回数据类型 1数组 2对象
     *
     * @return     array    ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-08-19T10:06
     */
    public static function getRow($where, $type = 1) {
        $res = self::where($where)->first();
        if (!$res) {
            return [];
        }

        if ($type == 1) {
            return $res->toArray();
        } else {
            return $res;
        }
    }

    /**
     * belongsto BaseModel.php
     * 根据条件获得结果
     *
     * @param      array  $where  The where
     * @param      array  $order  The order
     *
     * @return     array  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2020-04-07T15:19
     */
    public static function getRows($where = [], $order = ['id' => 'desc']) {
        $rowsObjs = self::where($where);
        foreach ($order as $key => $val) {
            $rowsObjs->orderBy($key, $val);
        }
        $rows = $rowsObjs->get();
        if ($rows) {
            return $rows->toArray();
        } else {
            return [];
        }
    }

    /**
     * belongsto BaseModel.php
     * 根据条件获取列表
     *
     * @param      array    $where    条件
     * @param      array    $order    排序
     * @param      integer  $start    起始
     * @param      integer  $perpage  取多少条数据
     *
     * @return     array    ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-08-23T15:08
     */
    public static function lists($where = [], $order = ['id' => 'desc'], $page = 1, $perPage = 10) {
        if (!$where) {
            return [];
        }
        if ($page < 1 || $perPage < 1) {
            return [];
        }

        $start = ($page - 1) * $perPage;
        $obj = self::where($where);

        foreach ($order as $key => $val) {
            $obj->orderBy($key, $val);
        }

        $obj->skip($start)->take($perPage);

        $listObjs = $obj->get();
        if ($listObjs) {
            return $listObjs->toArray();
        } else {
            return [];
        }

    }

    /**
     * belongsto BaseModel.php
     * 根据ID批量查询
     *
     * @param      array  $ids    一维ID数组  [1,2,3]
     *
     * @return     array   The lists by identifiers.
     *
     * @author     doufa
     * @since      2019-10-31T16:10
     */
    public static function getRowsByIds($ids = []) {
        if (!$ids) {
            return [];
        }
        $ids = array_unique($ids);
        $rowsObj = self::whereIn('id', $ids)->get();
        if ($rowsObj) {
            $rows = $rowsObj->toArray();
            return $rows;
        } else {
            return [];
        }
    }

    /**
     * belongsto BaseModel.php
     * 根据主键更新信息
     *
     * @param      int   $id          主键
     * @param      array    $updateData  The update data
     *
     * @return     boolean  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:08
     */
    public static function updateById($id, $updateData = []) {
        if (!$id || !$updateData) {
            return false;
        }
        return self::where(self::KeyName(), $id)->update($updateData);
    }

    /**
     * belongsto BaseModel.php
     * 缓存实例
     *
     * @return     object  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-07-09T16:09
     */
    public static function cache() {
        return Registry::get('di')['cache'] ?: object();
    }

    /**
     * belongsto BaseModel.php
     * 系统配置参数
     *
     * @return     array  ( description_of_the_return_value )
     *
     * @author     assad
     * @since      2019-08-20T15:11
     */
    public static function config() {
        return Registry::get('di')['config'] ?: [];
    }

    /**
     * belongsto BaseModel.php
     * 得到执行的SQL语句
     *
     * @param      string  $query  The query
     *
     * @return     string  The sql.
     *
     * @author     assad
     * @since      2019-09-16T15:22
     */
    public static function getSql($query) {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

}