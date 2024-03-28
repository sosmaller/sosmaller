<?php
/**
 * Created by PhpStorm.
 * User: ycs
 * Date: 2020/8/11
 * Time: 上午11:33
 */

namespace App\Jobs;

class TestJob extends BaseJob
{

    public function handle()
    {
        // Work to run this job
        var_dump($this->params);
        // 测试重试3次逻辑
        throw new \InvalidArgumentException(
            'Supplied $args must be an array.'
        );
        //var_dump($this->params);
    }

}

