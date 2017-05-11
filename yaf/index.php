<?php
define("APP_PATH", dirname(__FILE__));
define("ENV", "DEV");
$app = new Yaf\Application(APP_PATH . "/conf/application.ini");
$app->bootstrap()->run();