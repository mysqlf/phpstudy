<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        if (IS_POST) {
            $username=I('post.username');
            $password=I('post.password');
            $verify=I('post.verify');
            if (empty($username)) {$this->error('用户名不能为空');}
            if (empty($password)) {$this->error('密码不能为空');}
            if (empty($verify)) {$this->error('验证码不能为空');}
            if (!check_verify($verify)) {$this->error('验证码不正确');}
            $where['username']=$username;
            $where['password']=md5(md5($password));
            $where['status']=1;
            $Admin=M('Admin');
            $return=$Admin->where($where)->find();
            if($return){
                session('uname',$return['username']);
                session('uid',$return['uid']);
                session('gid',$return['group_id']);
                //更新登陆信息
                $Admin->uid = $return['uid'];
                $Admin->last_login_ip = get_client_ip();
                $Admin->last_login_time = time();
                if ($Admin->save()) {
                    $this->success('登录成功',U('Index/index'));
                }else{
                    $this->error('用户数据更新失败');
                }    
            }else{
                $this->error('该用户不存在或密码错误');
            }
            return;
        }
        $this->display();
    }
    //登录验证码
    public function verify(){
        $config =    array(
            'imageW'      =>    290,
            'imageH'      =>    60,
            'fontSize'    =>    28,    // 验证码字体大小    
            'length'      =>    5,     // 验证码位数    
            'fontttf'     =>    '5.ttf',
         );
         ob_clean(); 
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }
    //退出
    public function logout(){
        $_SESSION=array();
        if(isset($_cookIE[session_name()])){
            setcookie(session_name(),'',time()-1,'/');
        }
        session_destroy();
        $this->success('退出成功',U('Login/index'));
    }
}