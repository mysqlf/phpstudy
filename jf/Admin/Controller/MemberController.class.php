<?php
namespace Admin\Controller;
use Think\Controller;
class MemberController extends CommonController {
    //首页
    public function index(){
        $Member=D('Member');
        $count= $Member->count();
        $Page = new \Think\Page($count,20);
        $show = $Page->show();
        $data=M('Member')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->display();
    }
    public function search(){
        $keyword=I('post.keyword');
        $Member=D('Member');
        $count= $Member->count();
        $Page = new \Think\Page($count,20);
        $show = $Page->show();
        $map['username']=array('like','%'.$keyword.'%');
        $map['name']=array('like','%'.$keyword.'%');
        $map['company']=array('like','%'.$keyword.'%');
        $map['_logic'] = 'OR';
        $data=M('Member')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->display('index');
    }
    public function info(){
        $Member=D('Member');
        $id=I('get.id');
        $cdata=$Member->find($id);
        $this->assign('data',$cdata);
        $this->display();
    }
    public function mod(){
        $Member=D('Member');
        $id=I('get.id');
        $cdata=$Member->find($id);
        $password=I('post.password');
        $password2=I('post.password2');
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
                    $this->success('操作成功',U('Member/index'));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $this->assign('data',$cdata);
        $this->display();
    }
    public function add(){
        $Member=D('Member');
        if (IS_POST) {
            if (!$Member->create()){     
                $this->error($Member->getError());
            }else{
                $Member->register_time = time();
                $Member->status = 1;
                if ($Member->add()) {
                    $this->success('操作成功',U('Member/index'));
                }else{
                    $this->error('操作失败');
                }
            }
        }
        $this->display();
    }
    //禁用启用管理员
    public function isno_status(){
        $id=I("get.id");
        $s=I("get.s");
        $Member=D('Member');
        $Member->id=$id;
        $Member->status=$s;
        if ($Member->save()) {
            $this->success('操作成功',U('Member/index'));
        }else{
            $this->error('操作失败');
        }
    }
    //删除管理员
    public function del(){
        $id=I("get.id");
        $Member=D('Member');
        $Member->id=$id;
        if ($Member->delete()) {
            $this->success('删除成功',U('Member/index'));
        }else{
            $this->error('删除失败');
        }
    }
    //会员导入
    public function import(){
        if (IS_POST) {
            //上传成功
            if($this->_upload()){
                $Member=M('Member');
                $data1=import_xls("./Uploads/xls/temp.xls");
                if(empty($data1)){
                    $this->error('导入数据不存在');
                }
                $data2=array();
                $array2=array();
                foreach($data1 as $key=>$value){
                    $key=$key-2;
                    if(!empty($value['A'])){
                        $data2[$key]['username']=$value['A'];
                        $data2[$key]['score']=$value['B'];
                        $data2[$key]['name']=$value['C'];
                        $data2[$key]['sex']=$value['D'];
                        $data2[$key]['age']=$value['E'];
                        $data2[$key]['phone']=$value['F'];
                        $data2[$key]['idcard']=$value['G'];
                        $data2[$key]['address']=$value['H'];
                        $data2[$key]['password']=md5(C('INITIAL_PASSWORD'));
                        $data2[$key]['last_reg_time']=time();
                        if($Member->where(array('username'=>$value['A']))->count()){
                            $this->error('存在重复用户名：'.$value['A']);
                        }
                        if($Member->where(array('phone'=>$value['F']))->count()){
                            $this->error('存在重复手机号码：'.$value['F']);
                        }
                        $array2[]=$value['A'];
                    }
                }
                if (count($array2) != count(array_unique($array2))) {
                    $this->error('表格文件中存在重复用户名');
                }
                $re=$Member->addAll($data2);
                if($re){
                    $this->success('导入成功',U('Member/index'));
                }else{
                    $this->error('导入失败');
                }
            }
            return;
        }
        $this->display();
    }
    //积分导入
    public function import2(){
        if (IS_POST) {
            //上传成功
            if($this->_upload()){
                $Member=M('Member');
                $data1=import_xls("./Uploads/xls/temp.xls");
                if(empty($data1)){
                    $this->error('导入数据不存在');
                }
                $data2=array();
                $array2=array();
                foreach($data1 as $key=>$value){
                    $key=$key-2;
                    if(!empty($value['A'])){
                        $data2[$key]['username']=$value['A'];
                        $data2[$key]['score']=$value['B'];
                        //$data2[$key]['name']=$value['C'];
//                        $data2[$key]['sex']=$value['D'];
//                        $data2[$key]['age']=$value['E'];
//                        $data2[$key]['phone']=$value['F'];
//                        $data2[$key]['idcard']=$value['G'];
//                        $data2[$key]['address']=$value['H'];
//                        $data2[$key]['password']=md5(C('INITIAL_PASSWORD'));
//                        $data2[$key]['last_reg_time']=time();
                        $array2[]=$value['A'];
                    }
                }
                if (count($array2) != count(array_unique($array2))) {
                    $this->error('表格文件中存在重复用户名');
                }
                foreach ($data2 as $key2=>$value2){
                    $re=$Member->where(array('username'=>$value2['username']))->setInc('score',$value2['score']);
                    if(!$re){
                        $this->error('会员'.$value2['username'].'积分导入失败');
                    }
                }
                $this->success('导入成功',U('Member/index'));
            }
            return;
        }
        $this->display();
    }
    //上传附件操作
    private function _upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls');// 设置附件上传类型
        $upload->rootPath  =      './Uploads/xls/'; // 设置附件上传根目录
        $upload->autoSub = false;
        $upload->saveName = 'temp';
        $upload->replace = true;
        // 上传单个文件
        $info   =   $upload->uploadOne($_FILES['member']);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            return true;
        }
    }
    public function export(){
        $Member=D('Member');
        $data=$Member->field("username,score,name,sex,age,phone,idcard,address")->order('id desc')->select();//查询数据
        $filename="会员导出_";
        $headArr=array("用户名","积分","姓名","性别","年龄","手机号码","身份证号码","详细地址");
        $array=array();
        foreach($data as $key=>$value){
            $array[$key]=$value;
            $array[$key]['phone']=$value['phone']." ";
            $array[$key]['idcard']=$value['idcard']." ";
        }
        export_xls($filename,$headArr,$array);
    }
}