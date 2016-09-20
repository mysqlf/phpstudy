<?php
/*phpinfo();*/
$redis=new Redis();
$redis->connect('127.0.0.1',6379,0);
#$redis->FLUSHALL();
for ($i=0; $i <100000 ; $i++) { 
    $redis->hset("port:$i",'age',$i);
}
$arr=$redis->hget('port:99999','age');
var_dump($arr);
echo "\n";
/*$redis->set('Q','123');
$redis->set('W','123');

print_r($redis->keys('*'));
echo "<br>";
print_r($redis->get('Q'));
$redis->del('Q');
echo "<br>";
print_r($redis->keys('*'));
echo "<br>";
print_r($redis->ping());
echo "<br>";*/


//list操作
/*$redis->lpush('A','redis');
$redis->lpush('A','nosql');
$redis->lpush('A','mysql');
$redis->lpush('A','innodb');*/
/*$len=$redis->llen('A');
print_r($len);
//BEFORE之前
//$redis->linsert('A','BEFORE','mysqli','sqlserver');
//after之后
//$redis->linsert('A','after','mysqli','sqlserver');
$arr=$redis->lrange('A',0,$len);
print_r($arr);
//将列表source中的最后一个元素(尾元素)弹出，并返回给客户端。
//将source弹出的元素插入到列表destination，作为destination列表的的头元素。
$redis->rpoplpush('A','B');


$len=$redis->llen('A');
$arr=$redis->lrange('A',0,$len);
print_r($arr);

$len=$redis->llen('B');
$arr=$redis->lrange('B',0,$len);
print_r($arr);*/
//根据下标取值
//$va=$redis->lindex('A',15);
//print_r($va);
//删除超出范围的数据
#$redis->ltrim('A',0,$len-3);
//删除指定的值
#$redis->lrem('A','nosql',2);
//修改对应下标的值
//$redis->lset('A',2,'mysqli');
/*$len=$redis->llen('A');
print_r($len);
echo "<br>";
$arr=$redis->lrange('A',0,$len);
print_r($arr);*/
//hash 操作
/*单个赋值*/
/*$redis->hset("port:1",'age',12);
$redis->hset("port:1",'sex','man');
$redis->hset("port:1",'no',1);
//批量数组赋值
$redis->hMset("port:2",array("age"=>3,'sex'=>'g','no'=>2));
//获取单个值
$arr=$redis->hget('port:1','age');
echo "<br>";
print_r($arr);
echo "<br>";
//获取key多少
print_r($redis->hlen('port:1'));
获取具体的key
$key=$redis->hKeys('port:1');
echo "<br>";
print_r($key);
echo "<br>";
//获取多个key的值
$arr=$redis->hMget('port:1',array('sex','no'));
print_r($arr);
echo "<br>";
$list=$redis->hgetall('port:1');
print_r($list);
//获得所有的值,相当于select"*" 
$value=$redis->hvals('port:1');
echo "<br>";
print_r($value);*/


//无序集合操作
//set
/*$redis->sadd('S',3);//[1,2,3,4,5,6,5,4,3,2,1]添加一组数据加入的值是'array',而不是一列值,

$arr=$redis->sGetMembers('S');
print_r($arr);
$len=$redis->scard('S');
echo "<br>";
print_r($len);
$arr=$redis->sinter('S');
print_r($arr);
$redis->sadd('S',array(1,2,3,4,5,6,5));
#$redis->srem('S',[1,2,3,4,5,6,5]);
$arr=$redis->sinter('S');
$str=$arr['1'];
print_r($str);
echo "<br>";
$check=is_string($str);
print_r($check);*/

//有序集合
/*$redis->zadd('SS','1',12);
$redis->zadd('SS','1',11);
$redis->zadd('SS','1',13);
$redis->zadd('SS','1',10);
$redis->zadd('SS','1',17);
$redis->zadd('SS','1',13);
$redis->zadd('SS','1',10);
$redis->zadd('SS','1',17);


$redis->zadd('SS','2',19);
$redis->zadd('SS','2',11);
$redis->zadd('SS','2',13);
$redis->zadd('SS','2',10);
$redis->zadd('SS','2',17);
$redis->zadd('SS','2',13);
$redis->zadd('SS','2',10);
$redis->zadd('SS','2',17);
//检查集合内元素个数
$len=$redis->zcard('SS');
print_r($len);
echo "\n";
//统计范围内元素个数
$len=$redis->zcount('SS',1,3);
print_r($len);*/
#$redis->FLUSHALL();
?>