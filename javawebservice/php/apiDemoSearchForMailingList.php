<?php
    require_once 'api.php';

    $host = "192.168.2.19";

    $api = new CoremailAPI;
    try {
        if (!$api->open($host)) {
            echo("API Connect fail : " . $api->getErrorCode() . " - " . $api->getErrorString() . "\n");

        } else if (!$api->cmd(array(
                        "cmd"    => 302,
                        "attrs"  => "user_id&domain_name&forwardmaillist&is_system_cos&user_status",
                        "filter" => ["name" => 'forwardmaillist', "op" => 6, "val" => "%admin@dev92.com%"],
                ))
                || $api->getErrorCode() != 0
        ) {
            echo("API listUser fail : " . $api->getErrorCode() . " : " . $api->getErrorString() . "\n");

        } else {
            echo("API listUser ok: " . var_export($api->getResult(), true) . "\n");
        }
    } catch (Exception $e) {
        $api->close();
        throw $e;
    }
    $api->close();




