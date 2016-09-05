<?php
return array(
    //Auth权限设置
    'AUTH_CONFIG'=>array(
        'AUTH_ON'      =>true,//权限认证
        'AUTH_TYPE'    =>1,//认证方式，1为实时认证；2为登录认证。
        'AUTH_GROUP'    =>'bz_auth_group',//角色表名称
        'AUTH_GROUP_ACCESS'    =>'bz_admin',//用户和角色关系表名
        'AUTH_RULE'    =>'bz_auth_rule',//权限规则表名称
        'AUTH_USER'    =>'bz_admin',// 用户信息表
    ),
    'ADMINISTRATOR'=>array('1'),//超级管理员用户ID
);