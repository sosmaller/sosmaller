<?php

namespace App\Controllers;

use Exception;
use App\Traits\BaseTrait;


/**
 * @desc 后台开发继承类
 * 鉴权：通过子类定义属性 $allow_method
 * 字段定义: 通过子类重写方法 _filed
 * 条件筛选：通过子类重写方法 _where
 * 前置方法：通过子类定义方法 _before_$method
 */

class AdminController
{
    use BaseTrait;

    protected $method;
    protected $controller;
    protected $request;

    protected $model_name;//定义需要调运的模型
    protected $allow_method = [];//允许访问的控制器

    public function __construct($params = '')
    {
        #初始化
        $this->init();
        $this->method = $params['method'];
        $this->controller = $params['controller'];
        $this->request = app('request');

        #访问鉴权
        $this->checkAccess();
        #前置方法
        $_before = '_before_' . $this->method;
        method_exists($this, $_before) && $this->$_before();
    }

    /** 系统鉴权 */
    public function checkAccess()
    {
        if (in_array($this->method, $this->allow_method)) {
            return true;
        }
        return $this->error('无权访问');
    }

    /** 首页列表 */
    public function index()
    {
        $data = $this->_list();
        $this->success('', $data);
    }

    /** 获取单条记录 */
    public function get()
    {
        $id = $this->request->get('id');
        if (!$id) {
            $this->error('参数错误');
        }
        $field = $this->_field();
        $order = $this->_order();
        $where = $this->_where();
        array_push($where, ['id', '=', $id]);
        $model = $this->getModel();
        $data = $model::getOne($where, $field, $order);
        $this->success('', $data);
    }

    /** 添加记录 */
    public function add()
    {
        $model = $this->getModel();
        $params = $this->request->all();
        $attrs = $model->getAttribute();
        $data = array_intersect_key($params, $attrs);
        unset($data['id']);
        if ($model::add($data, true)) {
            $this->success('添加成功');
        }
        $this->error('添加失败');
    }

    /** 修改记录 */
    public function edit()
    {
        $id = $this->request->get('id');
        if (!$id) {
            $this->error('参数错误');
        }
        $model = $this->getModel();
        $params = $this->request->all();
        $attrs = $model->getAttribute();
        $data = array_intersect_key($params, $attrs);
        $where = $this->_where();
        array_push($where, ['id', '=', $data['id']]);
        if ($model::edit($where, $data)) {
            $this->success('修改成功');
        }
        $this->error('修改失败');
    }

    /** 修改状态 */
    public function status()
    {
        $id = $this->request->get('id');
        if (!$id) {
            $this->error('参数错误');
        }
        $status = $this->request->get('status');
        $model = $this->getModel();
        $where = $this->_where();
        array_push($where, ['id', '=', $id]);
        if ($model::edit($where, ['status' => $status])) {
            $this->success('修改成功');
        }
        $this->error('修改失败');
    }

    /** 删除记录 */
    public function del()
    {
        $id = $this->request->get('id');
        if (!$id) {
            $this->error('参数错误');
        }
        $where = $this->_where();
        array_push($where, ['id', '=', $id]);
        $model = $this->getModel();
        if ($model::edit($where, ['status' => -1])) {
            $this->success('删除成功');
        }
        $this->error('删除失败');
    }

    /** 数据列表 */
    protected function _list()
    {
        $field = $this->_field();
        $order = $this->_order();
        $page = $this->_page();
        $group = $this->_group();
        $where = $this->_where();
        $model = $this->getModel();
        $data = $model::getAll($where, $field, $page, $order, $group);
        return $data;
    }

    /** 查询条件 */
    protected function _where()
    {
        return $where = [];
    }

    /** 查询字段 */
    protected function _field()
    {
        return $field = [];
    }

    /** 排序字段 */
    protected function _order()
    {
        return $field = [];
    }

    /** 分组 */
    protected function _group()
    {
        return $group = [];
    }

    /** 分页查询 */
    protected function _page()
    {
        return $page = [];
    }

    /**
     * @desc 用 controller 解析需要的 model
     * @return mixed|string
     * @throws Exception
     */
    protected function getModel()
    {
        if (!$this->model_name) {
            $array = explode('\\', $this->controller);
            $controller = end($array);
            $this->model_name = explode('Controller', $controller)[0];
        }
        $model = 'App\\Models' . '\\' . ucfirst($this->model_name) . 'Model';
        if (!class_exists($model)) {
            throw new Exception('The model ' . $this->model_name . ' is not exists');
        }
        return new $model();
    }


}
