<?php
return array(
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'xinlong', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'cuso45h2o', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'sst_', // 数据库表前缀
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),

    'TOKEN_ON'  => true,
    'TOKEN_NAME'=> '__hash__',
    'TOKEN_TYPE'=> 'md5',
    'TOKEN_RESET' => true,

    'WECHAT' => array(
        'token'=>'xinlong',
        'appid'=>'wx9fde904b8287f304',
        'appsecret'=>'255c0fb46af625673f2898a3804c611f',
        'encodingaeskey'=>'KjwO71MdKbnopa795lwu1YVpFvTtKFJh8jfHJN6vLob',
    ),
);
