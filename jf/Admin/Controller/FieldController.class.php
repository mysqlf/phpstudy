<?php
namespace Admin\Controller;
use Think\Controller;
class FieldController extends CommonController {
    //首页
    public function index(){
        $mid=I('get.mid');
        $this->assign('mid',$mid);
        $Field=D('Field');
        $data=$Field->where(array('mid'=>$mid))->order('sort')->select();
        $this->assign('data',$data);
        $this->display();
    }
    //增加
    public function add(){
        $mid=I('get.mid');
        $this->assign('mid',$mid);
        $Field=D('Field');
        if (IS_POST) {
            if (!$Field->create()){     
                $this->error($Field->getError());
            }else{
                $rt=$Field->addField($_POST);
                if ($rt) {
                    $num=M('Field')->where(array('mid'=>$mid))->field('sort')->order('sort desc')->find();
                    if (empty($num)) {
                        $Field->sort=1;
                    }else{
                        $Field->sort=$num['sort']+1;
                    }
                    if ($Field->add()) {
                        $this->success('添加成功',U('Field/index',array('mid'=>$mid)));
                    }else{
                        $this->error('添加失败');
                    }
                }else{
                    $this->error('字段建表操作失败');
                }
            }
        }
        $this->display();
    }
    // 修改
    public function mod(){
        $mid=I('get.mid');
        $this->assign('mid',$mid);
        $Field=D('Field');
        if (IS_POST) {
            if (!$Field->create()){     
                $this->error($Field->getError());
            }else{
                if ($Field->save()) {
                    $this->success('操作成功',U('Field/index',array('mid'=>$mid)));
                }else{
                    $this->error('操作失败');
                }
            }
            return;
        }
        $id=I('get.id');
        $data=$Field->find($id);
        $this->assign('data',$data);
        $this->display();
    }
    //删除
    public function del(){
        $id=I("get.id");
        $mid=I("get.mid");
        $Field=D('Field');
        $Field->id=$id;
        $fd['mid']=$mid;
        $fd['name']=I('get.name');
        $rt=$Field->deleteField($fd);
        if ($rt) {
            if ($Field->delete()) {
                $this->success('删除成功',U('Field/index',array('mid'=>$mid)));
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('数据字段删除失败');
        } 
    }
    //禁用启用
    public function isno_status(){
        $id=I("get.id");
        $mid=I("get.mid");
        $s=I("get.s");
        $Field=D('Field');
        $Field->id=$id;
        $Field->status=$s;
        if ($Field->save()) {
            $this->success('操作成功',U('Field/index',array('mid'=>$mid)));
        }else{
            $this->error('操作失败');
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
        $Field=D('Field');
        if ($Field-> where(array('id'=>$id))->setField('sort',$sort)) {
            $this->success('操作成功',U('Field/index'));
        }else{
            $this->error('操作失败');
        }
    }
}