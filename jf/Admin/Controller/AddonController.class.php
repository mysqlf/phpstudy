<?php
namespace Admin\Controller;
use Think\Controller;
class AddonController extends CommonController  {
    //扩展
    public function message(){
        $id=I('post.id');
        $is_read=I('post.is_read');
        if (empty($id)) {
            $this->error('ID不存在');
        }
        $Message=M('Doc_message');
        $Message->id =$id;
        $Message->is_read =1;
        if ($Message->save()) {
            $this->success('数据查成功');
        }else{
            $this->error('数据查看失败');
        }
    }
}