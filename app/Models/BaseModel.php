<?php

namespace App\Models;

use Exception;

class BaseModel
{
    protected static $tableSuffix = ''; //分表
    protected static $connection = ''; //指定数据库
    protected static $table = ''; //指定表
    protected static $timestamps = true;

    public static function getTable()
    {
        return static::$table;
    }

    /**
     * 分表设置
     * @param string $key
     * @return string|void
     */
    public static function setTableSuffix($key = '')
    {
        if ($key === '') return;
        return static::$tableSuffix = '_' . $key; //用key分表
    }


    /**
     * 查询条件封装
     * @param $where
     * 让 where条件 支持数组格式的查询参数
     * 注意：这个函数不支持其他查询方式,只支持以下格式的where：
     *
     *    ['status', '>', 200]                     ---> where('status', '>', 200)
     *    ['status', '<', 200]                     ---> where('status', '<', 200)
     *    ['status', '=', 200]                     ---> where('status', '=', 200)
     *    ['status', '<>', 200]                    ---> where('status', '<>', 200)
     *    ['field', 'in', ['normal', 'deleted']]    ---> where field in('normal', 'deleted')
     *    ['field', 'not in', ['normal','deleted']] ---> where field not in('normal', 'deleted')
     *    ['field', 'like', 'normal']               ---> where filed like '%normal%'
     * @return string
     * @throws Exception
     */
    public static function buildQuery($where)
    {
        if (!is_array($where)) {
            throw new Exception("Where is must a array" . print_r($where, true));
        }
        $_where = '';
        foreach ($where as $item) {
            if (!is_array($item) || count($item) != 3) {
                throw new Exception("Where is must has three params" . print_r($item, true));
            }
            list($key, $opt, $val) = $item;
            $_where && $_where = ($_where . ' and ');
            if ($opt === 'in' || $opt === 'not in') {
                if (!is_array($val)) {
                    throw new Exception("In query must give a array：" . print_r($item, true));
                }
                $_where .= $key . ' ' . $opt . '("' . implode('","', $val) . '")';
            } elseif ($opt === 'like') {
                $_where .= $key .' '. $opt . " '%$val%'";
            } else {
                $_where .= $key .' '. $opt . " '$val'";
            }
        }
        return $_where;
    }


    /**
     * @param string $where
     * @param string[] $field
     * @param array $order
     * @param array $param
     * @return mixed
     * @throws Exception
     */
    public static function getOne($where, $field = ['*'], $order = [], $param = [])
    {
        if (!is_array($field)) {
            throw new Exception("Field is must a array" . print_r($field, true));
        }
        if (!is_array($order)) {
            throw new Exception("Order is must a array" . print_r($order, true));
        }

        $table = static::getTable();
        $field = $field ? implode(',', $field) : '*';
        $where = self::buildQuery($where);
        $where = $where ? 'where ' . $where : '';
        $order = (isset($order[0]) && isset($order[1])) ? " order by {$order[0]} {$order[1]}" : '';

        $sql = "select {$field} from {$table} {$where} {$order} limit 1";
        return self::query($sql);
    }

    /**
     * 获取批量数据
     * @param $where array use buildQuery
     * @param $field array eg ['id','title']
     * @param $order array eg ['id','desc']
     * @param $page array eg ['limit'=>10,'offset'=>10]
     * @param $group String
     * @param $param array extend eg ['suffix'=>'2018_07']
     * @return mixed
     * @throws Exception
     */
    public static function getAll($where, $field = ['*'], $page = [], $order = [], $group = '', $param = [])
    {
        if (!is_array($field)) {
            throw new Exception("GetAll Field is must a array" . print_r($field, true));
        }
        if (!is_array($order)) {
            throw new Exception("Order is must a array" . print_r($order, true));
        }

        $table = static::getTable();
        $field = $field ? implode(',', $field) : '*';
        $where = self::buildQuery($where);
        $where = $where ? 'where ' . $where : '';
        $group = $group ? "group by {$group}" : '';
        $order = (isset($order[0]) && isset($order[1])) ? "order by {$order[0]} {$order[1]}" : '';
        $limit = isset($page['limit']) ? "limit {$page['limit']}" : '';
        $offset = isset($page['offset']) ? "offset {$page['offset']}" : '';
        $sql = "select {$field} from {$table} {$where} {$group} {$order} {$limit} {$offset}";
        return self::query($sql, $prepare = [], $query_model = 'all');
    }


    /**
     * 插入数据
     * @param $data
     * @param $getId boolean is return insertId
     * @param $param array extend eg ['suffix'=>'2018_07']
     * @return mixed
     * @throws Exception
     */
    public static function add($data, $getId = false, $param = [])
    {
        if (!is_array($data)) {
            throw new Exception("Data is must a array" . print_r($data, true));
        }
        array_walk($data, function (&$val) {
            $val = addslashes($val);
        });
        $keys = implode(',', array_keys($data));
        $values = implode("','", array_values($data));
        $table = static::getTable();
        $sql = "insert into {$table} ({$keys}) values ('{$values}')";
        return self::exec($sql, $prepare = [], $getId);
    }

    /**
     * 更新数据
     * @param $where
     * @param $data
     * @return mixed
     * @throws Exception
     */
    public static function edit($where, $data)
    {
        if (!is_array($data)) {
            throw new Exception("Data is must a array" . print_r($data, true));
        }
        $set = '';
        foreach ($data as $key => $value) {
            $set .= $key . "='" . addslashes($value) . "',";
        }
        $set = trim($set, ',');
        $table = static::getTable();
        $where = self::buildQuery($where);
        $where = $where ? 'where ' . $where : '';
        $sql = 'update ' . $table . ' set ' . $set . ' ' . $where;
        return self::exec($sql, $prepare = []);
    }

    /**
     * 获取数量
     * @param $where
     * @return mixed
     * @throws Exception
     */
    public static function getCount($where)
    {
        $table = static::getTable();
        $where = self::buildQuery($where);
        $where = $where ? 'where ' . $where : '';
        $sql = 'select count(*) as count from ' . $table . ' ' . $where;
        $res = self::query($sql);
        return isset($res['count']) ? intval($res['count']) : 0;
    }

    /**
     * 获取模型名称
     */
    public static function getModel()
    {
        $model = get_called_class();
        return new $model();
    }

    /**
     * @desc 查询方法只支持 select
     * @param $sql
     * @param array $prepare
     * @param string $query_model
     * @return mixed
     */
    public static function query($sql, $prepare = [], $query_model = 'row')
    {
        return app('db')->connection(static::$connection ? static::$connection : 'default')->query($sql, $prepare, $query_model);
    }

    /**
     * @desc  sql执行方法
     * @param $sql
     * @param array $prepare
     * @param bool $getId
     * @return mixed
     */
    public static function exec($sql, $prepare = [], $getId = false)
    {
        return app('db')->connection(static::$connection ? static::$connection : 'default', 'write')->exec($sql, $prepare, $getId);
    }
}