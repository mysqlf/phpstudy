<?php
    require_once 'api.php';

    class TestCoremailAPI {

        var $host = "192.168.2.19";
        var $uid = "wolf@chitone.com.cn";
        var $org_id = "a";

        function testSpecificCall() {
            $api = new CoremailAPI;
            if (!$api->open($this->host)) {
                echo("API Connect fail : " . $api->getErrorCode() . " - " . $api->getErrorString() . "\n");
            } else if (!$api->getUserInfo($this->uid, array("user_id" => "", "domain_name" => "")) || $api->getErrorCode() != 0) {
                echo("API getUserInfo fail : " . $api->getErrorCode() . " - " . $api->getErrorString() . "\n");
            } else {
                echo("API getUserInfo ok: " . $api->getResult() . "\n");
            }
            $api->close();
        }

        function testGenericCall() {
            $api = new CoremailAPI;
            if (!$api->open($this->host)) {
                echo("API Connect fail : " . $api->getErrorCode() . " - " . $api->getErrorString() . "\n");
            } else if (!$api->call("cmd=3&user_at_domain=" . $this->uid, array("user_id" => "", "domain_name" => "")) || $api->getErrorCode() != 0) {
                echo("API getUserInfo fail : " . $api->getErrorCode() . " - " . $api->getErrorString() . "\n");
            } else {
                echo("API getUserInfo ok: " . $api->getResult() . "\n");
            }
            $api->close();
        }

        function testCmdForStructuralResult() {
            $api = new CoremailAPI;
            if (!$api->open($this->host)) {
                echo("API Connect fail : " . $api->getErrorCode() . " - " . $api->getErrorString() . "\n");
            } else if (!$api->cmd(array("cmd" => 302, "org_id" => $this->org_id)) || $api->getErrorCode() != 0) {
                echo("API listUser fail : " . $api->getErrorCode() . " : " . $api->getErrorString() . "\n");
            } else {
                echo("API listUser ok: " . var_export($api->getResult(), true) . "\n");
            }
            $api->close();
        }
    }

    $test = new TestCoremailAPI;
    // $case->host = "xxx" ;
    // $case->uid = "admin" ;
    // $case->org_id = "lwr" ;
    $test->testSpecificCall();
    $test->testGenericCall();
    $test->testCmdForStructuralResult();

