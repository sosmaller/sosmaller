<?php

namespace App\Models;

use Exception;

class BaseMongoModel
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
        $condition = ['=' => '$eq', '<>' => '$ne', '>' => '$gt', '<' => '$lt', '>=' => '$gte', '<=' => '$lte', 'in' => '$in', 'not in' => '$nin', 'like' => '$regex'];
        if (!is_array($where)) {
            throw new Exception("Where is must a array" . print_r($where, true));
        }
        $_where = [];
        foreach ($where as $item) {
            if (!is_array($item) || count($item) != 3) {
                throw new Exception("Where is must has three params" . print_r($item, true));
            }
            list($key, $opt, $val) = $item;
            if (in_array($opt, ['in', 'not in'])) {
                if (!is_array($val)) {
                    throw new Exception("In query must give a array：" . print_r($item, true));
                }
            }
            if ($opt === 'like') {
                $_where[$key][$condition[$opt]] = '.*' . $val . '.*';
            } else {
                $_where[$key][$condition[$opt]] = $val;
            }

        }
        return $_where;
    }
    

    /**
     * 获取一条数据
     * @param $where array use buildQuery
     * @param $field array eg ['id','title']
     * @param $order array eg ['id','desc']
     * @param $param array extend eg ['suffix'=>'2018_07']
     * @return mixed
     * @throws Exception
     */
    public static function getOne($where, $field = [], $order = [], $param = [])
    {
        if (!is_array($field)) {
            throw new Exception("Field is must a array" . print_r($field, true));
        }
        if (!is_array($order)) {
            throw new Exception("Order is must a array" . print_r($order, true));
        }

        $where = self::buildQuery($where);

        // 查询的字段
        $field && $options['projection'] = array_fill_keys($field, 1);
        $options['projection']['_id'] = 0;
        $options['limit'] = 1;

        // 排序
        $order && $options['sort'] = [$order[0] => (isset($order[1]) && $order[1] === 'desc') ? 1 : -1];

        $result = self::query($where, $options);
        return isset($result[0]) ? $result[0] : [];
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
    public static function getAll($where, $field = [], $page = [], $order = [], $group = '', $param = [])
    {
        if (!is_array($field)) {
            throw new Exception("Field is must a array" . print_r($field, true));
        }
        if (!is_array($order)) {
            throw new Exception("Order is must a array" . print_r($order, true));
        }

        $where = self::buildQuery($where);
        // 查询的字段
        $field && $options['projection'] = array_fill_keys($field, 1);
        $options['projection']['_id'] = 0;

        // 排序
        $order && $options['sort'] = [$order[0] => (isset($order[1]) && $order[1] === 'desc') ? -1 : 1];

        // 分页
        $page && $options['limit'] = isset($page['limit']) ? $page['limit'] : 10;
        $page && $options['skip'] = isset($page['offset']) ? $page['offset'] : 0;

        return self::query($where, $options);
    }

    /**
     * 添加一条或多条数据
     * @param $data
     * @param $getId boolean is return insertId
     * @param $param array extend eg ['suffix'=>'2018_07']
     * @return mixed
     * @throws Exception
     */
    public static function add($data, $getId = false, $param = [])
    {
        return app('mongodb')->connection(static::$connection ? static::$connection : 'default')->insert(static::getTable(), $data, $getId);
    }

    /**
     * 更新数据
     * @param $where
     * @param $data
     * @param bool $getId
     * @return mixed
     * @throws Exception
     */
    public static function edit($where, $data)
    {
        $where = self::buildQuery($where);
        return app('mongodb')->connection(static::$connection ? static::$connection : 'default')->update(static::getTable(), $where, ['$set' => $data]);
    }

    /**
     * 删除一条或多条数据
     * @param $where 查询条件
     * @return mixed
     */
    public static function delete($where)
    {
        $where = self::buildQuery($where);
        return app('mongodb')->connection(static::$connection ? static::$connection : 'default')->delete(static::getTable(), $where);
    }

    /**
     * 获取数量
     * @param $where
     * @return mixed
     * @throws Exception
     */
    public static function getCount($where)
    {
        $where = self::buildQuery($where);
        return app('mongodb')->connection(static::$connection ? static::$connection : 'default')->count(static::getTable(), $where);
    }

    /**
     * @desc 原始查询 -- 封装的方法不满足时可以直接调运
     * @param $where
     *     ['status' => ['$gt' => 200]]  大于
     *     ['status' => ['$lt' => 200]]  小于
     *     ['status' => ['$gte' => 200]]  大于等于
     *     ['status' => ['$lte' => 200]]  小于等于
     *     ['status' => ['$lte' => 200, '$gt' => 100]]  大于且小于等于
     *     ['status' => ['$eq' => 200]]   等于
     *     ['status' => 200] 等于
     *     ['status' => ['$ne' => 200]] 不等于
     * and ['status' => 200, 'title' => 'test']
     * or  ['$or' => [['status' => 200], ['title' => 'test']]
     *
     * @param array $options
     *        [
     *           'projection' => ['field1' => 1, 'field2' => 1], //
     *           'sort' => [status => -1], // -1 降序 1 升序
     *           'skip' => 0, // 页数-1 默认0
     *        ]
     * @return mixed
     * @throws Exception
     */
    public static function query($where, $option)
    {
        return app('mongodb')->connection(static::$connection ? static::$connection : 'default')->select(static::getTable(), $where, $option);
    }

}