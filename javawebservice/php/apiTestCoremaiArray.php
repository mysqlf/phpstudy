<?php

    require_once 'api.php';
    $host = "92.rd.mt";

    $api = new CoremailAPI;
    $api->setReportErrors();
    $api->setCharset("GB18030");
    $api->open($host);

    $api->cmd(["cmd"    => 24,  // GET_ORG_INFO
               "org_id" => "a",
               "attrs"  => new CoremailArray(
                       "org_id",
                       "org_name",
                       "org_creation_date"
               ),
    ]);
    $api->reportServerFail();
    echo "org info: ", print_r($api->getResult(), true), "\n";

    echo "\n";

    $api->cmd(["cmd"         => 302, // LIST_USERS
               "org_id"      => "a",
               "org_unit_id" => "",
               "attrs"       => new CoremailArray(
                       "user_id",
                       "domain_name",
                       "true_name",
                       "user_creation_date"
               ),
               "filter"      => new CoremailArray([
                       "name" => "user_status",
                       "op"   => 0,             // EQUALS
                       "val"  => 0,             // ACTIVE

               ], [
                       "name" => "user_id",     // LIKE
                       "op"   => 6,
                       "val"  => "test%",
               ]),
               "limit"       => 2,
    ]);
    $api->reportServerFail();
    echo "list user result: ", print_r($api->getResult()["u"], true), "\n";

    $api->close();

