<?php
namespace Admin\Controller;
use Think\Controller;
class ModelController extends CommonController {
    //首页
    public function index(){
        $Model=D('Model');
        $data=$Model->select();
        $this->assign('data',$data);
        $this->display();
    }
    //增加
    public function add(){
        $Model=D('Model');
        if (IS_POST) {
            //检测新建的表是否存在
            $mark=I('post.mark');
            if ($Model->checkTableExist($mark)) {
                $this->error('该数据表已经存在，请换其它表名');
            }
            if (!$Model->create()){     
                $this->error($Model->getError());
            }else{
                if ($Model->add()) {
                    $this->success('添加成功',U('Model/index'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
        $this->display();
    }
    // 修改
    public function mod(){
        $Model=D('Model');
        if (IS_POST) {
            if (!$Model->create()){     
                $this->error($Model->getError());
            }else{
                if ($Model->save()) {
                    $this->success('操作成功',U('Model/index'));
                }else{
                    $this->error('操作失败');
                }
            }
            return;
        }
        $id=I('get.id');
        $data=$Model->find($id);
        $this->assign('data',$data);
        $this->display();
    }
    //删除
    public function del(){
        $id=I("get.id");
        $Model=D('Model');
        //查询该模型下是否有分类
        $rcdata=M('Category')->where(array('mid'=>$id))->count();
        if ($rcdata) {
            $this->error('请先删除该模型下的分类');
        }
        //查询该模型下的数据
        $mdata=$Model->find($id);
        $tabename="Doc_".$mdata['mark'];
        $rcdata2=M($tabename)->count();
        if ($rcdata2) {
            $this->error('请先删除该模型下的数据');
        }
        if ($Model->delTable($id)) {
            $Model->id=$id;
            if ($Model->delete()) {
            $this->success('删除成功',U('Model/index'));
            }else{
                $this->error('删除失败');
            }
        }else{
            $this->error('数据表删除失败');
        }
    }
    //禁用启用
    public function isno_status(){
        $id=I("get.id");
        $s=I("get.s");
        $Model=D('Model');
        $Model->id=$id;
        $Model->status=$s;
        if ($Model->save()) {
            $this->success('操作成功',U('Model/index'));
        }else{
            $this->error('操作失败');
        }
    }
}