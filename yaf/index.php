<?php
define("APP_PATH", dirname(__FILE__));
define("DOMAIN_NAME", 'http://www.yaf.com');
define("ENV", "DEV");
$app = new Yaf\Application(APP_PATH . "/conf/application.ini");
$app->bootstrap()->run();
