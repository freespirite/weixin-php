<?php
return array(
    'ADM_TITLE' => 'GROUPS管理平台',
    //'配置项'=>'配置值'
    'TMPL_TEMPLATE_SUFFIX'=>'.php',
    
    //'URL_CASE_INSENSITIVE'=>true,

    //'URL_ROUTER_ON' => true,

    'URL_MODEL' => '2',

    //'DEFAULT_CONTROLLER'    => 'main', // 默认控制器名称

    'MODULE_ALLOW_LIST' => array(
        'Home',
        'Admin',
    ),
    
    'ADMIN_SESSION'     => 'wxadmin',
    'DEFAULT_MODULE'    => 'Admin',

    'SITE_URL'          => 'http://localhost/gdsell/weixin-php/Public/index.php',//网站地址

    'TMPL_PARSE_STRING'  =>array(
        '__PUBLIC__'     =>  __ROOT__.'/data', // 更改默认的/Public 替换规则
//        '__HOME__'       => 'home',
//        '__ADMIN__'      => 'back',
    ),
    
    'APP_KEY'               => '24638cfe066a05532b9220bcf38308a5',
    
    'NO_LOGIN'              => 0,    //免登陆时间（天）

    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'weixin',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '123456',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'wx_',    // 数据库表前缀
    'DB_PARAMS'             =>  array(), // 数据库连接参数
    'DB_DEBUG'              =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    
    'SHOW_PAGE_TRACE'       => TRUE,
    'UCLOUD_CDN' => 'http://linebar.ufile.ucloud.com.cn/',
    
    'DATA_CACHE_TYPE'       => 'Memcache',
    /*
    'MEMCACHE_HOST'         => '',
    'MEMCACHE_PORT'         => '',
    'DATA_CACHE_TIMEOUT'    => '',
    */
);

