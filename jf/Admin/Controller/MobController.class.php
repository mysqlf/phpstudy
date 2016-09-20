<?php
namespace Admin\Controller;
use Think\Controller;
class MobController extends Controller {
    public function _initialize () {
        if(ACTION_NAME != "login"){
            //验证登陆,没有登陆则跳转到登陆页面
            if(!isset($_SESSION['uname']) || $_SESSION['uname']==''||!isset($_SESSION['uid']) || $_SESSION['uid']==''){
                $this->redirect('Mob/login');
                exit;
            }else{
                define('UID',$_SESSION['uid']);//用户ID
            }
        }
    }
    //登陆首页
    public function index(){
        $Member=M('Member');
        $member_num=$Member->count();
        $map1['last_reg_time']  = array('egt',strtotime("-1 week"));
        $member_new_num=$Member->where($map1)->count();
        $this->assign('member_num',$member_num);
        $this->assign('member_new_num',$member_new_num);
        $Order=M('Order');
        $order_new_num=$Order->where(array('status'=>0))->count();
        $this->assign('order_new_num',$order_new_num);
        $this->assign('title',"系统首页");
        $this->display();
    }
    //首页
    public function login(){
        if (IS_POST) {
            $username=I('post.username');
            $password=I('post.password');
            $verify=I('post.verify');
            if (empty($username)) {$this->error('用户名不能为空');}
            if (empty($password)) {$this->error('密码不能为空');}
            $where['username']=$username;
            $where['password']=md5(md5($password));
            $where['status']=1;
            $Admin=M('Admin');
            $return=$Admin->where($where)->find();
            if($return){
                session('uname',$return['username']);
                session('uid',$return['uid']);
                session('gid',$return['group_id']);
                //更新登陆信息
                $Admin->uid = $return['uid'];
                $Admin->last_login_ip = get_client_ip();
                $Admin->last_login_time = time();
                if ($Admin->save()) {
                    $this->success('登录成功',U('Mob/index'));
                }else{
                    $this->error('用户数据更新失败');
                }
            }else{
                $this->error('该用户不存在或密码错误');
            }
            return;
        }
        $this->display();
    }
    //退出
    public function logout(){
        $_SESSION=array();
        if(isset($_cookIE[session_name()])){
            setcookie(session_name(),'',time()-1,'/');
        }
        session_destroy();
        $this->success('退出成功',U('Mob/login'));
    }
    //会员管理
    public function member(){
        $Member=D('Member');
        $count= $Member->count();
        $Page = new \Think\Page($count,20);
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('theme',"<ul data-am-widget=\"pagination\" class=\"am-pagination am-pagination-select\"><li class=\"am-pagination-prev \">%UP_PAGE% </li><li class=\"am-pagination-select\">共 %TOTAL_ROW% 条</li><li class=\"am-pagination-next \">%DOWN_PAGE%</li></ul>");
        $show = $Page->show();
        $data=M('Member')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->assign('title',"会员管理");
        $this->display();
    }
    //添加会员
    public function member_add(){
        $Member=D('Member');
        if (IS_POST) {
            if (!$Member->create()){
                $this->error($Member->getError());
            }else{
                $Member->last_reg_time = time();
                $Member->status = 1;
                if ($Member->add()) {
                    $this->success('操作成功',U('Mob/member'));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $this->display();
    }
    //禁用启用管理员
    public function member_status(){
        $id=I("get.id");
        $s=I("get.s");
        $Member=D('Member');
        $Member->id=$id;
        $Member->status=$s;
        if ($Member->save()) {
            $this->success('操作成功',U('Mob/member'));
        }else{
            $this->error('操作失败');
        }
    }
    //会员搜索
    public function member_search(){
        $keyword=I('post.keyword');
        $Member=D('Member');
        $count= $Member->count();
        $Page = new \Think\Page($count,20);
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('theme',"<ul data-am-widget=\"pagination\" class=\"am-pagination am-pagination-select\"><li class=\"am-pagination-prev \">%UP_PAGE% </li><li class=\"am-pagination-select\">共 %TOTAL_ROW% 条</li><li class=\"am-pagination-next \">%DOWN_PAGE%</li></ul>");
        $show = $Page->show();
        $map['username']=array('like','%'.$keyword.'%');
        $map['name']=array('like','%'.$keyword.'%');
        $map['_logic'] = 'OR';
        $data=M('Member')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->assign('title',"会员管理");
        $this->display('member');
    }
    public function member_info(){
        $Member=D('Member');
        $id=I('get.id');
        $cdata=$Member->find($id);
        $this->assign('data',$cdata);
        $this->assign('title',"会员管理");
        $this->display();
    }
    public function member_mod(){
        $Member=D('Member');
        $id=I('get.id');
        $cdata=$Member->find($id);
        $password=I('post.password');
        if (IS_POST) {
            if (!$Member->create()){
                $this->error($Member->getError());
            }else{
                if ($password) {
                    if (empty($password2)) {
                        $this->error("确认密码不能为空！");
                    }
                    if ($password != $password2) {
                        $this->error("您两次输入的账号密码不一致！");
                    }
                    $Member->password=md5($password);
                }else{
                    $Member->password=$cdata['password'];
                }
                if ($Member->save()) {
                    $this->success('操作成功',U('Mob/member_info',array('id'=>$id)));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $this->assign('data',$cdata);
        $this->assign('title',"会员信息修改");
        $this->display();
    }
    //删除会员
    public function member_del(){
        $id=I("get.id");
        $Member=D('Member');
        $Member->id=$id;
        if ($Member->delete()) {
            $this->success('删除成功',U('Mob/member'));
        }else{
            $this->error('删除失败');
        }
    }
    //首页
    public function order(){
        $status=I('get.status');
        if($status==2 || $status==1){
            if($status==2){
                $status=0;
            }
            $map['status']=$status;
        }
        $Order=D('Order');
        $count= $Order->count();
        $Page = new \Think\Page($count,20);
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('theme',"<ul data-am-widget=\"pagination\" class=\"am-pagination am-pagination-select\"><li class=\"am-pagination-prev \">%UP_PAGE% </li><li class=\"am-pagination-select\">共 %TOTAL_ROW% 条</li><li class=\"am-pagination-next \">%DOWN_PAGE%</li></ul>");
        $show = $Page->show();
        $data=M('Order')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->assign('status',$status);
        $this->assign('title',"订单管理");
        $this->display();
    }
    public function order_search(){
        $keyword=I('post.keyword');
        $Order=D('Order');
        $count= $Order->count();
        $Page = new \Think\Page($count,20);
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $Page->setConfig('theme',"<ul data-am-widget=\"pagination\" class=\"am-pagination am-pagination-select\"><li class=\"am-pagination-prev \">%UP_PAGE% </li><li class=\"am-pagination-select\">共 %TOTAL_ROW% 条</li><li class=\"am-pagination-next \">%DOWN_PAGE%</li></ul>");
        $show = $Page->show();
        $map['ord_num']=array('like','%'.$keyword.'%');
        $data=M('Order')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->assign('title',"订单管理");
        $this->display('order');
    }
    public function order_info(){
        $Order=D('Order');
        if (IS_POST) {
            if (!$Order->create()){
                $this->error($Order->getError());
            }else{
                $Order->status=1;
                if ($Order->save()) {
                    $this->success('操作成功',U('Mob/order'));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $id=I('get.id');
        $data=$Order->find($id);
        $this->assign('data',$data);
        $this->assign('title',"订单详细");
        $this->display();
    }
    //退单
    public function order_back(){
        $id=I("get.id");
        $score=r_od_score($id);
        $Order=D('Order');
        $oinfo=$Order->find($id);
        $Order->id=$id;
        if ($Order->delete()) {
            mb_score($oinfo['mbid'],$score);
            $this->success('退单成功');
        }else{
            $this->error('退单失败');
        }
    }
}