<?php

namespace App\Models;

class UserMongoModel extends BaseMongoModel
{
    protected static $connection = 'default'; //指定数据库
    protected static $table = 'user'; //指定表
}
