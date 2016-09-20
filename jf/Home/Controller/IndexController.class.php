<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
        $this->assign('home',1);
        $this->display();
    }
    public function register(){
        if (IS_POST) {
            $Member=D('Member');
             if (!$Member->create()){     
                $this->error($Member->getError());
            }else{
                 $Member->register_time = time();
                 $Member->status = 1;
                // $Member->score = 200;
                 if ($Member->add()) {
                    $this->success("注册成功，请登录！",U('Index/lists'));
                }else{
                    $this->error("注册失败，请重新注册！");
                }
            }
            return;
        }
        $this->display();
    }
    
//    public function SendMsg()
//    {
//            $phone=I('post.phone');
//            // 需要确保当前的 PHP 环境支持 Soap
//            // 创建 soap 客户端
//            $soap = new SoapClient('http://115.29.52.221:24663/Service/?wsdl');
//            $result = $soap->__soapcall('SubmitSms', array(
//                '用户名',
//                '密码',
//                '手机号',
//                '这是一条测试信息 via PHP【签名】' // 这里的签名要替换成用户自己的签名，消息内容要符合已审核模板之一
//                ));
//
//            $this->error("短信发送失败");
//    }
    
    public function lists(){
        $Doc_product=M('Doc_product');
        $keyword=I('post.keyword');
        if ($keyword) {
            $map['title']=array('like',"%".$keyword."%");
        }
        $cid=I('get.cid');
        if ($cid) {
            $map['cid']=$cid;
            $this->assign('cid',$cid);
        }
        $level=I('get.level');
        if ($level) {
            $map['level']=$level;
            $this->assign('level',$level);
        }
        $score1=I('get.score1');
        $score2=I('get.score2');
        if ($score1 && $score2) {
            $map['score']=array('between',array($score1,$score2));
            $this->assign('score1',$score1);
            $this->assign('score2',$score2);
        }
        $count= $Doc_product->where($map)->count();
        $Page = new \Think\Page($count,16);
        //兼容手机端
        if(ismobile() || $_SESSION['IS_MOBILE']){
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $Page->setConfig('theme',"%UP_PAGE% %DOWN_PAGE%");
        }
        $show = $Page->show();
        $data=$Doc_product->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('update_time desc')->select();
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->assign('active',2);
        $this->display();
    }
    public function msg(){
        $Doc_product=M('Doc_product');
        $id=I('get.id');
        $data=$Doc_product->find($id);
        $this->assign('data',$data);
        $cid=$data['cid'];
        $cate=M('Category')->find($cid);
        $this->assign('cate',$cate);
        $this->assign('active',2);
        $this->display();
    }
    public function page(){
        $Doc_article=M('Doc_article');
        $cid=I('get.cid');
        if ($cid) {
            $data=$Doc_article->where(array('cid'=>$cid))->find();
            $cate=M('Category')->find($cid);
        }
        $id=I('get.id');
        if ($id) {
            $data=$Doc_article->find($id);
            $cate=M('Category')->find($data['cid']);
        }
        $this->assign('data',$data);
        $this->assign('cate',$cate);
        $this->assign('cid',$cate['id']);
        $this->display();
    }
    public function login(){
        if(IS_POST){
            $Member=D('Member');
            $password=I('post.password');
            $map['username']=I('post.username');
            $map['password']=md5($password);
            $re=$Member->where($map)->find();
            if ($re){
                if ($re['status']==0) {
                    $this->error("您的账户还未启用，请联系管理员开启！");
                }else{
                    $Member->last_login_time = time();
                    $Member->id =$re['id'];
                    $Member->save();
                    session('mname',$re['username']);
                    session('mid',$re['id']);
                    $this->success("登录成功",U('Member/index'));
                }
            }else{
                $this->error("登录失败，用户名或密码不正确",U('Member/index'));
            }
        }else{
            $this->display();
        }
    }
    //兼容APP
    public function category(){
        $list=M('Category')->where(array('pid'=>1))->select();
        $this->assign('list',$list);
        $this->assign('active',2);
        $this->display();
    }
    public function menu(){
        $cid=I('get.cid');
        $list=M('Doc_article')->where(array('cid'=>$cid))->select();
        $this->assign('list',$list);
        $this->assign('active',2);
        $this->assign('cid',$cid);
        $this->display();
    }
    //退出
    public function logout(){
        session('mname',null); 
        session('mid',null);
        $this->success('退出成功',U('Index/index'));
    }
}