<?php
/**
 * Created by PhpStorm.
 * User: ycs
 * Date: 2020/8/11
 * Time: 上午11:33
 */

namespace App\Jobs;

use App\Traits\BaseTrait;

class BaseJob
{
    use BaseTrait;

    public $params;

    public function __construct()
    {
        defined('QUEUE_WORKER') or define('QUEUE_WORKER', true);
        $this->init();
    }

    public function before()
    {
        // ... Set up environment for this job
    }

    public function perform()
    {
        method_exists($this, 'handle') && $this->handle();
    }

    public function after()
    {
        // ... Remove environment for this job
    }
}