<?php

namespace App\Controllers;

use App\Jobs\TestJob;
use App\Models\UserModel;
use Sosmall\Components\Db;
use App\Models\UserMongoModel;
use Sosmall\Components\Queue;

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
        $this->response(0, ['hello word']);
    }

}