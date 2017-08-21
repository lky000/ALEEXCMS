<?php
//配置文件
$root = request()->root();
define('__ROOT__',str_replace('/index.php','',$root));
define('__UPLOADS__','/myCMS/public/uploads');
return [
    'view_replace_str'       => [
        '__UPLOADS__' => '/myCMS/public/uploads',
    ],
];