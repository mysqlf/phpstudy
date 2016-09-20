<?php
return array(
    //'配置项'=>'配置值'
    'LOAD_EXT_CONFIG' => 'alipay',//扩展配置
    //分布式数据库配置定义
    // 'DB_DEPLOY_TYPE'=> 1, // 设置分布式数据库支持
    // 'DB_TYPE'       => 'mysql', //分布式数据库类型必须相同
    // 'DB_HOST'       => 'mysql1.zzidc.ha.cn,mysql1.zzidc.ha.cn',
    // 'DB_NAME'       => 'huijie', //如果相同可以不用定义多个
    // 'DB_USER'       => 'huijie_f,huijie_f',
    // 'DB_PWD'        => 'huijie1966,huijie1966',
    // 'DB_PORT'       => '14015,14015',
    // 'DB_PREFIX'     => 'bz_',
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'jf', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => '', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'bz_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'TAGLIB_BUILD_IN'           =>'Cx,My', //自定义标签库
    'TAGLIB_BEGIN'=>'[',//标签库标签开始标签 
    'TAGLIB_END'=>']',//标签库标签结束标记
    'DATA_CACHE_CHECK' =>  true,   // 数据缓存是否校验缓存
    'DB_FIELDS_CACHE' => false, //字段缓存
);
/*return array(
	//'配置项'=>'配置值'
    'LOAD_EXT_CONFIG' => 'alipay',//扩展配置
    //分布式数据库配置定义
    // 'DB_DEPLOY_TYPE'=> 1, // 设置分布式数据库支持
    // 'DB_TYPE'       => 'mysql', //分布式数据库类型必须相同
    // 'DB_HOST'       => 'mysql1.zzidc.ha.cn,mysql1.zzidc.ha.cn',
    // 'DB_NAME'       => 'huijie', //如果相同可以不用定义多个
    // 'DB_USER'       => 'huijie_f,huijie_f',
    // 'DB_PWD'        => 'huijie1966,huijie1966',
    // 'DB_PORT'       => '14015,14015',
    // 'DB_PREFIX'     => 'bz_',
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '59.49.39.69', // 服务器地址
    'DB_NAME'   => 'jifenduihuan', // 数据库名
    'DB_USER'   => 'every', // 用户名
    'DB_PWD'    => 'every', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 'bz_', // 数据库表前缀
    'DB_CHARSET'=> 'utf8', // 字符集
    'TAGLIB_BUILD_IN'           =>'Cx,My', //自定义标签库
    'TAGLIB_BEGIN'=>'[',//标签库标签开始标签 
    'TAGLIB_END'=>']',//标签库标签结束标记
    'DATA_CACHE_CHECK' =>  true,   // 数据缓存是否校验缓存
    'DB_FIELDS_CACHE' => false, //字段缓存
);*/