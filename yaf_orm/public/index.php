<?php
define("APP_PATH", dirname(dirname(__FILE__)));
define("UP_PATH", APP_PATH.'/Uploads/');
define("DOMAIN_NAME", 'http://www.yaf_orm.com');
$app = new Yaf\Application(APP_PATH . "/conf/application.ini"); 
$app->bootstrap()->run();
