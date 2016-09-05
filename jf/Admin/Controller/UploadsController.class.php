<?php
namespace Admin\Controller;
use Think\Controller;
class UploadsController extends CommonController  {
	Public function _initialize(){
    	// 初始化的时候检查用户权限
    	if(@$_REQUEST['session'] && ($session_id=$_REQUEST['session']) !=session_id()){ 
		    session_destroy(); 
		    session_id($session_id);
		    @session_start();    
		}
        if(!isset($_SESSION['uname']) || $_SESSION['uname']==''||!isset($_SESSION['uid']) || $_SESSION['uid']==''){
            $this->redirect('Login/index');
        }
	}
    public function index(){
        $config = array(    
            'maxSize'    =>    819200,         // 设置附件上传大小,800K
            'savePath'   =>    '',    
            'exts'       =>    array('jpg', 'gif', 'png'), 
            'autoSub'    =>    false,    
        );
        $upload = new \Think\Upload($config);
        $info=$upload->upload();
        if(!$info) {
            $data['error']  = 1;
            $data['message']=$upload->getError();
        }else{
            //生产缩略图
            $Image = new \Think\Image();
            $Image_size=I('post.att')?I('post.att'):"150,150";
            $Size=explode(",",$Image_size);
            //录入数据库
            $Uploads=D('Uploads');
            foreach($info as $file){
                $Image->open('./Uploads/'.$file['savename']);
                $Image->thumb($Size[0],$Size[1],\Think\Image::IMAGE_THUMB_CENTER)->save('./Uploads/th_'.$file['savename']);
                $Uploads->name=$file['savepath'].$file['savename'];
                $Uploads->title=substr($file['name'], 0, -4);;  
                $Uploads->add();
            }
            $data['error']  = 0;
        }
        $this->ajaxReturn($data);
    }
    //文件选择页面
    public function choose(){
        $type=I('get.type');//上传类型，默认1单文件，2多文件
        $name=I('get.name');
        $images_size=I('get.att');
        if ($name) {
            $Uploads=M('Uploads');
            $count= $Uploads->count();
            $Page = new \Think\Page($count,18);
            $show = $Page->show();
            $Filelist=M('Uploads')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
            $this->assign('paging',$show);
            $this->assign('list',$Filelist);//分配文件列表
            $this->assign('name',$name);//分配字段名称
            $this->assign('img_size',$images_size);//分配字段名称
            //判断模板文件
            switch ($type){
                case 1:
                    $this->display('one');
                    break;
                case 2:
                    $this->display('more');
                    break;
                case 3:
                    $this->display('ueditor');
                    break;
                default:
                    $this->display('one');
            }
        }else{
            echo "错误，字段名称未定义";
        }	
    }
}