<?php
defined('SYSTEM_PATH') or define('SYSTEM_PATH', dirname(__DIR__).'/');
require_once  __DIR__.'/../vendor/autoload.php';
$app = new SoSmaller\Application();
return $app;
