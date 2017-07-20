<?php
use Illuminate\Database\Capsule\Manager as DB;
/**
 * 登录
 */
class LoginController extends Yaf\Controller_Abstract {
    public function indexAction() {//默认Action        
        $this->getView()->display('public/login.php');
    }
    /**
     * [loginAction 登录逻辑]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-13
     * @return   [type]     [description]
     */
    public function loginAction(){
        filter();
        $request = $this->getRequest();
        $data=$request->getPost();
        if (!empty($data)) {
            $user=UserModel::get_user_by_name($data['username']);
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
    /**
     * [logoutAction 退出]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-13
     * @return   [type]     [description]
     */
    public function logoutAction(){
        del_session('user');
        success('退出成功',site_url('login/login'));
    }
}
