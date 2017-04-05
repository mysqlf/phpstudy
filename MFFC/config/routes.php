<?php
/**
 * 注册路由
 */
use NoahBuscher\Macaw\Macaw;

Macaw::get('fuck',function(){
    echo "OK";
});

Macaw::get('home', 'HomeController@home');

Macaw::get('(:all)', function($fu) {
  echo '未匹配到路由<br>'.$fu;
});
Macaw::dispatch();