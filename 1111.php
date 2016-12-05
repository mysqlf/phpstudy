<?php
set_time_limit(0);
$socket = fsockopen('192.168.2.19','6195',$error,$errorstringfsockopen,20);
var_dump($socket);
var_dump(get_resource_type($socket));
var_dump(stream_get_contents($socket));
$x=stream_socket_client('192.168.2.19:6195',$error,$errorstringfsockopen,20);
var_dump($x);
var_dump(get_resource_type($x));
var_dump(stream_get_contents($x));

$host = "192.168.2.19";
$port = 6195;
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
/*
  "ALTER TABLE oa_apply CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_addwork CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_annex CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_attach CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_car CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_edit_log CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_leave CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_leave_hand CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_modifyrest CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_out CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_punch CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_rest CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_travel CONVERT TO CHARACTER SET utf8;",
  "ALTER TABLE oa_apply_turnover CONVERT TO CHARACTER SET utf8;",


#社区表字符集修改
  ALTER TABLE oa_article CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_article_hit CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_article_reply CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_company CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_depart CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_eat CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_meet CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_menu CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_message CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_process_template CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_socialcircle CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_template CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_template_comconf CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_template_version CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_user CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_user_binding CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_user_friends CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_user_info CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_user_main CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_user_socialcircle CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_user_template CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_user_token CONVERT TO CHARACTER SET utf8;
  */

/*

  ALTER TABLE oa_apply CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_addwork CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_annex CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_attach CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_car CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_edit_log CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_leave CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_modifyrest CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_out CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_punch CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_rest CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_travel CONVERT TO CHARACTER SET utf8;
  ALTER TABLE oa_apply_turnover CONVERT TO CHARACTER SET utf8;


 */

 /*
  ALTER TABLE oa_apply CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_addwork CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_annex CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_attach CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_car CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_edit_log CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_out CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_process CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_punch CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_rest CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_travel CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_turnover CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_apply_workdate CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_article CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_article_hit CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_article_reply CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_company CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_depart CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_eat CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_meet CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_menu CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_message CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_process_template CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_socialcircle CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_template CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_template_comconf CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_template_version CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_user CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_user_binding CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_user_friends CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_user_info CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_user_main CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_user_socialcircle CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_user_template CONVERT TO CHARACTER SET utf8mb4;
  ALTER TABLE oa_user_token CONVERT TO CHARACTER SET utf8mb4;
  */