<?php

namespace App\Controllers;

use App\Jobs\TestJob;
use App\Models\UserModel;
use SoSmaller\Components\Config;
use SoSmaller\Components\Db;
use App\Models\UserMongoModel;
use SoSmaller\Components\Queue;
use SoSmaller\Components\Redis;
use SoSmaller\Components\Request;

class IndexController extends BaseController
{
    public function index()
    {
        //$res = UserModel::getAll([]);
        //$res = $this->cache->get('abc');
        //var_dump($res);die;
        //$res = UserModel::query('select * from  cuishou_user where id>0',[],'all');
        //var_dump($res);die;

        // $res = Db::query('','select * from  dict_itagtel where id=10');

        // $res = Db::query('','select * from  dict_itagtel where id=10');


        //$res = $db->exec('','update dict_itagtel set company_name = "ccccc" where id=10');
        //$res = $db->query($db->getPdo('default'),'select * from  dict_itagtel where id=10');

        //var_dump($res);die;

        //foreach ($res as $row) {
        //print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
        //}
        //$res = UserMongoModel::getOne(['sid' => '82beabc0eaaa0f1a7e4837c92c330af1'], ['apikey']);
        //var_dump($res);
        //app('queue')->onQueue(TestJob::class, ['name' => 'yangchengsheng']);

        // $this->request = app('request');

        //$conf = Config::instance()->getConfig('mailer');

        //$res = Redis::instance()->set('conf',json_encode($conf));

        //$mailer = app('config')->getConfig('mailer');
        //$mailer = Config::instance()->getConfig('mailer');

        //$request = app('request')->get('name');

        //$request = Request::instance()->get('name');

        //app('queue')->onQueue();

        //Queue::instance()->onQueue();

        $this->response(0, ['hello']);
    }

}
