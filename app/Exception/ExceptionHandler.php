<?php

namespace App\Exception;

use App\Traits\BaseTrait;
use Exception;

/**
 * Class ExceptionHandler
 * @desc Exception catch
 */
class ExceptionHandler
{
    use BaseTrait;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        //E_ERROR,
        E_WARNING,
        E_PARSE,
        E_NOTICE,
        E_CORE_ERROR,
        E_CORE_WARNING,
        E_COMPILE_ERROR,
        E_COMPILE_WARNING,
        E_USER_ERROR,
        E_USER_WARNING,
        E_USER_NOTICE,
        E_STRICT,
        E_RECOVERABLE_ERROR,
        E_DEPRECATED,
        E_USER_DEPRECATED,
    ];

    /**
     * 打印错误信息
     * @param $message string 错误信息
     * @param $error_code  integer 错误类型
     */
    /**
     * @param Exception $e
     * @return bool
     * @throws Exception
     */
    public function report(Exception $e)
    {
        if (env('APP_DEBUG')) {
            exit($e->getMessage().PHP_EOL.$e->getTraceAsString());
        }
        if (!in_array($e->getCode(), $this->dontReport)) {
            if (defined('RESQUE_WORKER')) {
                throw $e; //worker抛出异常
            } elseif (php_sapi_name() == 'cli') {
                exit($e->getMessage().PHP_EOL.$e->getTraceAsString()); //cli模式
            } else{
                exit($this->response(1501));
            }
        }
        return true;
    }


}