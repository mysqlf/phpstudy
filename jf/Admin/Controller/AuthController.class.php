<?php
namespace Admin\Controller;
use Think\Controller;
class AuthController extends CommonController {
    //角色列表
    public function role(){
        $Auth_group=D('Auth_group');
        $data=$Auth_group->select();
        $this->assign('data',$data);
        $this->display();
    }
    //增加角色
    public function add_role(){
        $Auth_group=D('Auth_group');
        if (IS_POST) {
            if (!$Auth_group->create()){     
                $this->error($Auth_group->getError());
            }else{
                $Auth_group->rules=implode(',',I('post.rules'));
                if ($Auth_group->add()) {
                    $this->success('添加成功',U('Auth/Auth_role'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
        $rules=M('Auth_rule')->select();
        $this->assign('rules',$rules);
        $this->display();
    }
    //禁用启用角色
    public function role_status(){
        $id=I("get.id");
        $s=I("get.s");
        $Auth_group=D('Auth_group');
        $Auth_group->id=$id;
        $Auth_group->status=$s;
        if ($Auth_group->save()) {
            $this->success('操作成功',U('Auth/role'));
        }else{
            $this->error('操作失败');
        }

    }
    //修改权限
    public function mod_role(){
        $Auth_group=D('Auth_group');
        if (IS_POST) {
            if (!$Auth_group->create()){     
                $this->error($Auth_group->getError());
            }else{
                $Auth_group->rules=implode(',',I('post.rules'));
                if ($Auth_group->save()) {
                    $this->success('操作成功',U('Auth/role'));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $id=I("get.id");
        $data=$Auth_group->find($id);
        $this->assign('data',$data);
        $rules=M('Auth_rule')->select();
        $this->assign('rules',$rules);
        $this->display();
    }
    //删除角色
    public function del_role(){
        $id=I("get.id");
        $Auth_group=D('Auth_group');
        $Auth_group->id=$id;
        if ($Auth_group->delete()) {
            $this->success('删除成功',U('Auth/role'));
        }else{
            $this->error('删除失败');
        }
    }
    //权限列表
    public function rule(){
        $Auth_rule=D('Auth_rule');
        $data=$Auth_rule->select();
        $this->assign('data',$data);
        $this->display();
    }
    //增加权限
    public function add_rule(){
        $Auth_rule=D('Auth_rule');
        if (IS_POST) {
            if (!$Auth_rule->create()){     
                $this->error($Auth_rule->getError());
            }else{
                if ($Auth_rule->add()) {
                    $this->success('添加成功',U('Auth/rule'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
        $this->display();
    }
    //修改权限
    public function mod_rule(){
        $Auth_rule=D('Auth_rule');
        if (IS_POST) {
            if (!$Auth_rule->create()){     
                $this->error($Auth_rule->getError());
            }else{
                if ($Auth_rule->save()) {
                    $this->success('操作成功',U('Auth/rule'));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $id=I("get.id");
        $data=$Auth_rule->find($id);
        $this->assign('data',$data);
        $this->display();
    }
    //删除权限
    public function del_rule(){
        $id=I("get.id");
        $Auth_rule=D('Auth_rule');
        $Auth_rule->id=$id;
        if ($Auth_rule->delete()) {
            $this->success('删除成功',U('Auth/rule'));
        }else{
            $this->error('删除失败');
        }
    }
}