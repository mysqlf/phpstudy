<?php
use Illuminate\Database\Capsule\Manager as DB;

class LoginController extends Yaf\Controller_Abstract {
    public function indexAction() {//默认Action        
        $this->getView()->display('public/login.php');
    }
    public function loginAction(){
        filter();
        $request = $this->getRequest();
        $data=$request->getPost();
        if (!empty($data)) {
            $user=UserModel::get_user_by_name($data['username'])->toArray();
            if ($user['password']==md5($data['password'])) {
                set_session('user',$user);
                success('登录成功',site_url('Member/index'));
            }else{
                error('密码错误');
            }
        }else{
            error('账号错误');
        }

    }
}
