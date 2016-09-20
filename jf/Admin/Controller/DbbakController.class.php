<?php
namespace Admin\Controller;
use Think\Controller;
class DbbakController extends CommonController {
    //首页
    public function index(){
        $dir="Common/dbbak/";//备份目录
        $Dbbak = new \Vendor\Dbbak(C('DB_HOST'),C('DB_USER'),C('DB_PWD'),C('DB_NAME'));
        $type=I('get.type');
        // 备份
        if ($type==1) {
            if ($Dbbak->exportSql()) {
                $this->success('备份成功');
            }else{
                $this->error('备份失败');
            }
            return;
        }
        // 还原
        if ($type==2) {
            $name=I('get.name');
            if ($Dbbak->importSql($dir.$name)) {
                $this->success('还原成功');
            }else{
                $this->error('还原失败');
            }
            return;
        }
        $data=$Dbbak->getTables();
        $this->assign('dblist',$data);
        $this->display();
    }
    public function file(){
        $dir="Common/dbbak/";//备份目录
        $File = new \Vendor\File();
        $type=I('get.type');
        $name=I('get.name');
        if ($type==1) {
            if (!file_exists($dir.$name)){
                $this->error('该文件不存在');
            }
            if (unlink($dir.$name)) {
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
            return;
        }
        $filelist = $File -> get_dirs($dir);
        $this->assign('filelist',$filelist['file']);
        $this->display();
    }
}