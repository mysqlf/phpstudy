<?php
class mytest{
    public function index(){
        foreach ($variable as $key => $value) {
            # code...
        }
    }
    public function tets(){

    }


}
$tst=new mytest();

//测试foreach &value
//不建议使用引用,除非真的需要修改数组原本的值
//因为加了引用之后foreach不会自己释放$value这个引用
//需要用户自己手动释放
//在数据量很小用户很小的时候一点点内存是可以忽略,
//但是生产环境用户量大的时候这就是问题了

$arr=array(array('a'=>1,'b'=>2),);
foreach ($arr as $v) {
    $v['a']=$v['b']+$v['a'];
    $res=$v;print_r($res);
}
#print_r($arr);
$arr=array(array('a'=>1,'b'=>2), );
foreach ($arr as &$v) {
    $v['a']=$v['b']+$v['a'];
    $res=$v;print_r($res);
    unset($v);
}
print_r($arr);
#print_r($arr);
?>
