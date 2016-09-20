<?php
namespace Admin\Controller;
use Think\Controller;
class OnepageController extends CommonController {
    //首页
    public function index(){
        $Onepage = new \Think\Category();
        $data=M('Onepage')->order('sort asc')->select();
        $clist=$Onepage->getTree($data);
        $this->assign('data',$clist);
        $this->display();
    }
    public function add(){
        $Onepage=D('Onepage');
        $cid=I('post.cid');
        if (IS_POST) {
            if ($Onepage->where(array('cid'=>$cid))->find()) {
                $this->error('该分类已存在单页面');
            }
            if (!$Onepage->create()){     
                $this->error($Onepage->getError());
            }else{
                if ($Onepage->add()) {
                    $this->success('添加成功',U('Onepage/index'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
        $this->display();
    }
    public function mod(){
        $Onepage=D('Onepage');
        if (IS_POST) {
            if (!$Onepage->create()){     
                $this->error($Onepage->getError());
            }else{
                if ($Onepage->save()) {
                    $this->success('操作成功',U('Onepage/index'));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $id=I('get.id');
        $cdata=$Onepage->find($id);
        $this->assign('data',$cdata);
        $this->display();
    }
    public function del(){
        $id=I("get.id");
        $Onepage=D('Onepage');
        $Onepage->id=$id;
        if ($Onepage->delete()) {
            $this->success('删除成功',U('Onepage/index'));
        }else{
            $this->error('删除失败');
        }
    }
    //分类排序
    public function sort(){
        $id=I("get.id");
        $s=I("get.s");
        $Onepage=D('Onepage');
        $sort=$Onepage->field('sort')->find($id);
        $Onepage->id=$id;
        if ($s==0) {
            $Onepage->sort=$sort['sort']-1;
        }
        if ($s==1) {
            $Onepage->sort=$sort['sort']+1;
        }
        if ($Onepage->save()) {
            $this->success('操作成功',U('Onepage/index'));
        }else{
            $this->error('操作失败');
        }
    }
}