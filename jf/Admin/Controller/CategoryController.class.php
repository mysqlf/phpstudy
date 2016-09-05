<?php
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends CommonController {
    //首页
    public function index(){
        $Category = new \Think\Category();
        $data=M('Category')->order('sort asc')->select();
        $clist=$Category->getTree($data);
        $this->assign('data',$clist);
        $this->display();
    }
    public function add(){
        $Category=D('Category');
        if (IS_POST) {
            if (!$Category->create()){     
                $this->error($Category->getError());
            }else{
                $Category->sort=100;
                if ($Category->add()) {
                    $this->success('添加成功',U('Category/index'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
        $pid=I('get.id');
        if ($pid==0) {
            $this->assign('pname',"顶级分类");
            $this->assign('pid',0);
        }else{
            $pdata=$Category->field('name')->find($pid);
            $this->assign('pname',$pdata['name']);
            $this->assign('pid',$pid);
        }
        $modellist=M('Model')->field('id,title')->select();
        $this->assign('modellist',$modellist);
        $this->display();
    }
    public function mod(){
        $Category=D('Category');
        if (IS_POST) {
            if (!$Category->create()){     
                $this->error($Category->getError());
            }else{
                if ($Category->save()) {
                    $this->success('操作成功',U('Category/index'));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $id=I('get.id');
        $cdata=$Category->find($id);
        $this->assign('data',$cdata);
        $modellist=M('Model')->field('id,title')->select();
        $this->assign('modellist',$modellist);
        $this->display();
    }
    public function del(){
        $id=I("get.id");
        $Category=D('Category');
        //查询该分类下是否有文档
        $cdata=$Category->find($id);
        if ($cdata['mid']==-1) {
            $mdata['mark']="Onepage";
            $mname="Onepage";
        }else{
            $mdata=M('Model')->find($cdata['mid']);
            $mname="Doc_".$mdata['mark'];
        }
        if ($mdata['mark']) {
            $rt=M($mname)->where(array('cid'=>$id))->count();
            if ($rt) {
                $this->error('请先删除该分类下的数据');
            }
        }else{
            $this->error('该分类模型参数错误');
        }
        //检测是否有子类
        $rt2=$Category->where(array('pid'=>$id))->count();
        if ($rt2) {
            $this->error('请先删除该分类下的子类');
        }
        $Category->id=$id;
        if ($Category->delete()) {
            $this->success('删除成功',U('Category/index'));
        }else{
            $this->error('删除失败');
        }
    }
    //分类排序
    public function sort(){
        $id=I("post.id");
        $sort=I("post.sort");
        if(empty($id) || empty($sort)){
            $this->error('参数不存在');
        }
        $sort=intval($sort);
        $Category=D('Category');
        if ($Category-> where(array('id'=>$id))->setField('sort',$sort)) {
            $this->success('操作成功',U('Category/index'));
        }else{
            $this->error('操作失败');
        }
    }
}