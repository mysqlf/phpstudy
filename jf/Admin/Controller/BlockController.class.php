<?php
namespace Admin\Controller;
use Think\Controller;
class BlockController extends CommonController {
    //首页
    public function index(){
        $Block=D('Block');
        $count= $Block->count();
        $Page = new \Think\Page($count,18);
        $show = $Page->show();
        $data=M('Block')->limit($Page->firstRow.','.$Page->listRows)->order('id asc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->display();
    }
    //增加
    public function add(){
        $Block=D('Block');
        if (IS_POST) {
            if (!$Block->create()){     
                $this->error($Block->getError());
            }else{
                if ($Block->add()) {
                    $this->success('添加成功',U('Block/index'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
        $this->display();
    }
    // 修改
    public function mod(){
        $Block=D('Block');
        if (IS_POST) {
            if (!$Block->create()){     
                $this->error($Block->getError());
            }else{
                if ($Block->save()) {
                    $this->success('添加成功',U('Block/index'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
        $id=I('get.id');
        $data=$Block->find($id);
        $this->assign('data',$data);
        $this->display();
    }
    //删除
    public function del(){
        $id=I("get.id");
        $Block=D('Block');
        $Block->id=$id;
        if ($Block->delete()) {
            $this->success('删除成功',U('Block/index'));
        }else{
            $this->error('删除失败');
        }
    }
    //文件管理
    public function file(){
        $Uploads=M('Uploads');
        $type=I('get.type');
        if ($type==1) {
            $id=I('get.id');
            $data=$Uploads->find($id);
            $dir="./Uploads/";
            if (!file_exists($dir.$name)){
                $this->error('该文件不存在');
            }
            if (unlink($dir.$data['name']) && unlink($dir."th_".$data['name'])) {
                $Uploads->delete($id);
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
            return;
        }
        $count= $Uploads->count();
        $Page = new \Think\Page($count,30);
        $show = $Page->show();
        $Filelist=M('Uploads')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('list',$Filelist);//分配文件列表
        $this->display();
    }
}