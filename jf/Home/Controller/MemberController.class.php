<?php
namespace Home\Controller;
use Think\Controller;
//公共继承类
class MemberController extends CommonController {
    public function index(){
        $Member=D('Member');
        if (IS_POST) {
            if (!$Member->create()){     
                $this->error($Member->getError());
            }else{
                $password=I('post.password');
                $password2=I('post.password2');
                if ($password) {
                    if(empty($password2)){
                        $this->error("确认密码不能为空！");
                    }
                    if($password != $password2){
                        $this->error("您两次输入的账号密码不一致！");
                    }
                    $Member->password=md5($password);
                }else{
                    $mbdata=M('Member')->find(MID);
                    $Member->password=$mbdata['password'];
                }
                 if ($Member->save()) {
                    $this->success("修改成功",U('Member/index'));
                }else{
                    $this->error("修改失败");
                }
            }
            return;
        }
        $this->assign('active',4);
        $this->display();
    }
    //全部订单
    public function order(){
        $Order=D('Order');
        $map['mbid']=MID;
        $count= $Order->where($map)->count();
        $Page = new \Think\Page($count,5);
        //兼容手机端
        if(ismobile() || $_SESSION['IS_MOBILE']){
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $Page->setConfig('theme',"%UP_PAGE% %DOWN_PAGE%");
        }
        $show = $Page->show();
        $data=$Order->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->assign('active',4);
        $this->assign('status',3);
        $this->display();
    }
    //最新订单
    public function neworder(){
        $Order=D('Order');
        $map['mbid']=MID;
        $map['status']=0;
        $count= $Order->where($map)->count();
        $Page = new \Think\Page($count,5);
        //兼容手机端
        if(ismobile() || $_SESSION['IS_MOBILE']){
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $Page->setConfig('theme',"%UP_PAGE% %DOWN_PAGE%");
        }
        $show = $Page->show();
        $data=$Order->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->assign('active',4);
        $this->assign('status',0);
        $this->display();
    }
    //已发货订单
    public function oldorder(){
        $Order=D('Order');
        $map['mbid']=MID;
        $map['status']=0;
        $count= $Order->where($map)->count();
        $Page = new \Think\Page($count,5);
        //兼容手机端
        if(ismobile() || $_SESSION['IS_MOBILE']){
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $Page->setConfig('theme',"%UP_PAGE% %DOWN_PAGE%");
        }
        $show = $Page->show();
        $data=$Order->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->assign('active',4);
        $this->assign('status',1);
        $this->display();
    }
}