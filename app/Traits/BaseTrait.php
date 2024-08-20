<?php

namespace App\Traits;

trait BaseTrait
{
    /**
     * 产品前缀，用于区分不同项目；
     */
    protected $prefix;
    protected $cache;
    protected $pids;

    public function init()
    {
        $this->prefix = config('app_prefix');
        $this->pids = config("app_pids");
        $this->cache = \SoSmaller\Components\Redis::instance()->connection();
    }

    /** admin使用 操作成功 */
    protected function success($msg = '', $data = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        $return = ['status' => 0, 'msg' => $msg ? $msg : 'ok'];
        $data && $return['data'] = $data;
        exit(json_encode($return));

    }

    /** admin使用 操作失败 */
    protected function error($msg = '', $data = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        $return = ['status' => 1, 'msg' => $msg ? $msg : 'no'];
        $data && $return['data'] = $data;
        exit(json_encode($return));
    }

    /**
     * @author yangchengsheng
     * @desc 所有接口结构统一返回
     * @date 2018-07-09
     *
     * @param int $status
     * @param array $data
     * return json
     */
    protected function response($status, $data = [])
    {
        header('Content-Type: application/json; charset=utf-8');
        $conf = config('response.status');
        $msg = isset($conf[$status]) ? $conf[$status] : $conf[1501];
        $return = ['status' => $status, 'msg' => $msg];
        $data && $return['data'] = $data;
        exit(json_encode($return));
    }
}
