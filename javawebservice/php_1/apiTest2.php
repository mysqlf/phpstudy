<?php
    require_once 'api.php';

    function _w($str) {
        return iconv("GBK", "utf-8", $str);
    }

    $host = "92.rd.mt";
    $uid = "admin@dev92.com";

    $api = new CoremailAPI;
    try {
        if (!$api->open($host)) {
            echo "API Connect fail : " . $api->getErrorCode() . " - " . _w($api->getErrorString()) . "\n";
            return;
        }

        if ($api->call("cmd=-2", "")) {
            echo "API Verify ok.\n";
        } else {
            echo "API Verify fail : " . $api->getErrorCode() . " : " . _w($api->getErrorString()) . "\n";
        }

        if ($api->getUserInfo($uid, "true_name")) {
            echo "API getUserInfo ok: " . _w($api->getResult()) . "\n";
        } else {
            echo "API getUserInfo fail : " . $api->getErrorCode() . " : " . _w($api->getErrorString()) . "\n";
        }
    } catch (Exception $e) {
        $api->close();
        throw $e;
    }
    $api->close();




