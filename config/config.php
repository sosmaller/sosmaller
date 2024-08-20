<?php
return [
    'app_host' => 'www.sosmaller.cn',

    'app_prefix' => 'sosmaller',

    'app_pids' => [1],

    //路由表
    'router' => include_once("router.php"),

    //数据库
    'database' => include_once("database.php"),

    //响应状态码和code
    'response' => include_once("response.php"),

    //默认邮件发送配置
    'mailer' => [
        'mail_host' => 'smtp.263.net',
        'mail_port' => 25,
        'mail_encryption' => 'tls',
        'mail_username' => 'test@263.com',
        'mail_password' => 'test!@#test',
        'mail_from_address' => 'test@263.com',
        'mail_from_name' => '服务部',
        'mail_to_address' => ['杨先生' => '369363564@qq.com']
    ]
];
