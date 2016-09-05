<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        $this->assign('pagenum',M('Onepage')->count());
        $this->assign('articlenum',M('Doc_article')->count());
        $this->assign('productnum',M('Doc_product')->count());
        $this->assign('ordernum',M('Order')->where(array('status'=>0))->count());
        $this->display();
    }
    public function ClearCache(){
        $dir="./Runtime";
        if (deldir($dir)){
            $this->success('缓存清理成功');
        }else{
            $this->error('缓存清理失败');
        }
    }
}