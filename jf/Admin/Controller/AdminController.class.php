<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends CommonController {
    //首页
    public function index(){
        $Admin=D('Admin');
        $data=$Admin->select();
        $this->assign('data',$data);
        $this->display();
    }
    //首页
    public function add(){
        $Admin=D('Admin');
        if (IS_POST) {
            if (!$Admin->create()){     
                $this->error($Admin->getError());
            }else{
                $password=I('post.password');
                $Admin->password=md5(md5($password));
                if ($Admin->add()) {
                    $this->success('添加成功',U('Admin/index'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
        //角色列表
        $Role=M("AuthGroup");
        $rolelist=$Role->select();
        $this->assign('rolelist',$rolelist);
        $this->display();
    }
    public function mod(){
        $Admin=D('Admin');
        if (IS_POST) {
            $username=I('post.username');
            $password=I('post.password');
            $uid=I('post.uid');
            $admininfo=$Admin->find($uid);
            if($username!=$admininfo['username']){
                $re=$Admin->where(array('username'=>$username))->count();
                if($re){
                    $this->error('该用户名已被占用，请换一个试试！');
                }
            }
            if (!$Admin->create()){
                $this->error($Admin->getError());
            }else{
                if($password){
                    $Admin->password=md5(md5($password));
                }else{
                    $Admin->password=$admininfo['password'];
                }
                if ($Admin->save()) {
                    $this->success('修改成功',U('Admin/index'));
                }else{
                    $this->error('修改失败');
                }
            }
        }
        $uid=I('get.id');
        $data=$Admin->find($uid);
        $this->assign('data',$data);
        //角色列表
        $Role=M("AuthGroup");
        $rolelist=$Role->select();
        $this->assign('rolelist',$rolelist);
        $this->display();
    }
    //禁用启用管理员
    public function isno_status(){
        $id=I("get.id");
        $s=I("get.s");
        $Admin=D('Admin');
        $Admin->uid=$id;
        $Admin->status=$s;
        if ($Admin->save()) {
            $this->success('操作成功',U('Admin/index'));
        }else{
            $this->error('操作失败');
        }

    }
    //删除管理员
    public function del(){
        $id=I("get.id");
        $Admin=D('Admin');
        $Admin->uid=$id;
        if ($Admin->delete()) {
            $this->success('删除成功',U('Admin/index'));
        }else{
            $this->error('删除失败');
        }
    }
    // 管理员密码修改
    public function modpass(){
        if (IS_POST) {
            $Admin=D('Admin');
            if (!$Admin->create()){     
                $this->error($Admin->getError());
            }else{
                $password=I('post.password');
                if ($password) {
                    $Admin->password=md5(md5($password));
                }
                $Admin->uid=UID;
                if ($Admin->save()) {
                    $this->success('修改成功',U('Admin/modpass'));
                }else{
                    $this->error('没有修改任何项');
                }
            }
            return;
        }
        $this->display();
    }
    // 管理员资料修改
    public function modinfo(){
        $Admin=D('Admin');
        if (IS_POST) {
            if (!$Admin->create()){     
                $this->error($Admin->getError());
            }else{
                $Admin->uid=UID;
                if ($Admin->save()) {
                    $this->success('设置成功',U('Admin/modinfo'));
                }else{
                    $this->error('没有设置任何项');
                }
            }
            return;
        }
        $data=$Admin->find(UID);
        $this->assign('data',$data);
        $this->display();
    }
}