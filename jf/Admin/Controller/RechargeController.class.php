<?php
namespace Admin\Controller;
use Think\Controller;
class RechargeController extends CommonController {
    public function index(){
        $count= M('Recharge')->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $data=M('Recharge')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->display();
    }
    public function del(){
        $id=I("get.id");
        $Recharge=D('Recharge');
        $Recharge->id=$id;
        if ($Recharge->delete()) {
            $this->success('删除成功',U('Recharge/index'));
        }else{
            $this->error('删除失败');
        }
    }
}