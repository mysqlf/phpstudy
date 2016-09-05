<?php
namespace Admin\Controller;
use Think\Controller;
class DocumentController extends CommonController {
    public function index(){
        $cid=I('get.cid');
        $mid=I('get.mid');
        //模型数据
        $model_data=M('Model')->find($mid);
        $this->assign('mdata',$model_data);
        
        if ($cid) {
            $map['cid']=$cid;
            $this->assign('cid',$cid);
        }
        //获取该模型下所有文档
        $tablename="Doc_".$model_data['mark'];
        $Doc=M($tablename);
        $count= $Doc->where($map)->count();
        $Page = new \Think\Page($count,12);
        $show = $Page->show();

        $catelist=M("Category")->where(array('mid'=>$mid))->select();
        $this->assign('catelist',$catelist);

        $data=$Doc->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
        $this->assign('paging',$show);
        $this->assign('data',$data);
        if ($model_data['list_type']) {
            $this->display($model_data['list_type']);
        }else{
            $this->display();
        }
    }
    public function search(){
        $cid=I('post.cid');
        $mid=I('post.mid');
        $keyword=I('post.keyword');
        //模型数据
        $model_data=M('Model')->find($mid);
        $this->assign('mdata',$model_data);
        
        if ($cid) {
            $map['cid']=$cid;
            $this->assign('cid',$cid);
        }

        //获取该模型下所有文档
        $tablename="Doc_".$model_data['mark'];
        $Doc=M($tablename);
        $count= $Doc->where($map)->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $map['title']=array('like','%'.$keyword.'%');
        
        $catelist=M("Category")->where(array('mid'=>$mid))->select();
        $this->assign('catelist',$catelist);

        $data=$Doc->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
        $this->assign('paging',$show);
        $this->assign('data',$data);
        if ($model_data['list_type']) {
            $this->display($model_data['list_type']);
        }else{
            $this->display('index');
        }
    }
    public function add(){
        $mid=I('get.mid');
        $model_data=M('Model')->find($mid);
        $this->assign('mdata',$model_data);
        if (IS_POST) {
            $cid=I('cid');
            if ($cid) {
                $re=M('Category')->where(array('pid'=>$cid))->find();
                if ($re) {
                    $this->error('父类不能发内容，请选择其子类');
                }
            }
            $tablename="Doc_".$model_data['mark'];
            $Doc=D($tablename);
            if (!$Doc->create()){
                    $this->error($Doc->getError());
                }else{
                    $Doc->update_time=time();
                    if ($Doc->add()) {
                        $this->success('操作成功',U('Document/index',array('mid'=>$mid)));
                    }else{
                        $this->error('操作失败');
                    }
                }
         }
         //模型字段列表
        $field_data=M('Field')->where(array('mid'=>$mid,'status'=>1))->order('sort')->select();
        $this->assign('fdata',$field_data);
        $this->display();
    }
    public function mod(){
        $id=I('get.id');
        $mid=I('get.mid');
        $model_data=M('Model')->find($mid);
        $this->assign('mdata',$model_data);
        $tablename="Doc_".$model_data['mark'];
        $Doc=D($tablename);
        if (IS_POST) {
            if ($cid) {
                $re=M('Category')->where(array('pid'=>$cid))->find();
                if ($re) {
                    $this->error('父类不能发内容，请选择其子类');
                }
            }
            if (!$Doc->create()){
                    $this->error($Doc->getError());
                }else{
                    $Doc->update_time=time();
                    if ($Doc->save()) {
                        $this->success('操作成功',U('Document/index',array('mid'=>$mid)));
                    }else{
                        $this->error('操作失败');
                    }
                }
         }
         //模型字段列表
        $field_data=M('Field')->where(array('mid'=>$mid,'status'=>1))->order('sort')->select();
        $data=$Doc->find($id);
        $this->assign('data',$data);
        $fdata=array();
        if ($field_data && $data) {
            foreach ($field_data as $key => $value) {
                $value['f_data']=$data[$value['name']];
                $fdata[$key]=$value;
            }
            $this->assign('fdata',$fdata);
        }else{
            $this->error('表单控件初始化错误');
        }
        $this->display();
    }
    public function del(){
        $id=I("get.id");
        $mid=I('get.mid');
        $model_data=M('Model')->find($mid);
        $tablename="Doc_".$model_data['mark'];
        $Doc=D($tablename);
        $Doc->id=$id;
        if ($Doc->delete()) {
            $this->success('删除成功',U('Document/index',array('mid'=>$mid)));
        }else{
            $this->error('删除失败');
        }
    }
    public function delmore(){
        $id=I("post.id");
        $mid=I('post.mid');
        $model_data=M('Model')->find($mid);
        $tablename="Doc_".$model_data['mark'];
        $Doc=D($tablename);
        if (empty($id)) {
            $this->error('请选择要删除的内容');
        }
        $ids=implode(",",$id);
        $re=$Doc->where("id in (".$ids.")")->delete();
        if ($re) {
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }   
    }
}