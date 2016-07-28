<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/22
 * Time: 16:40
 */

class FindPasswordAction extends Action
{
    public function index()
    {
        $lasturl = I('get.str')?I('get.str'):I('get.redirect');
        $this->assign('lasturl',$lasturl);
        $this->display('FindPassword/find_username');
    }
    /**
     * 找回密码
     */
    public function getpassword()
    {
        $lasturl = I('post.redirect')?I('post.redirect'):I('post.str');
        $this->assign('lasturl',$lasturl);
        $account = I('post.account')?I('post.account'):"";
        $date['account'] = $account;
        session('account',$account);
        $url =APP_DOMIN.'Chitone-Account-verifyStatus';
        $res = urlencode(json_encode($date));
        $result = _get($url,$res);
        if ($result['status'] == 900000)
        {
            $content = "您输入的账号错误";
            $this->assign('content',$content);
            $this->assign('account',$account);
            $this->display('FindPassword/find_username');
        }
        else
        {
            if ($result['result']['bindMobile'])
            {
                $str = $result['result']['bindMobile'];
                $str = substr($str,0,3).'****'.substr($str,7,4);
                $this->assign('str',$str);
                $this->assign('bindMobile',$result['result']['bindMobile']);
                session('phone',$result['result']['bindMobile']);
                $this->display('FindPassword/find_verify');
            }
            else
            {
                if ($result['result']['bindEmail'])
                {
                    session('email',$result['result']['bindEmail']);
                    list($name, $server) = explode('@', $result['result']['bindEmail']);
                    $emailurl = 'http://mail.'.$server;
                    $this->sendmail();
                    $this->assign('emailurl',$emailurl);
                    $this->assign('bindEmail',$result['result']['bindEmail']);
                    $this->display('FindPassword/email');
                }
                else
                {
                    if (preg_match("/^0?(13[0-9]|15[012356789]|18[012356789]|14[57])[0-9]{8}$/",$account))
                    {
                        $str = $account;
                        $str = substr($str,0,3).'****'.substr($str,7,4);
                        $this->assign('str',$str);
                        $this->assign('bindMobile',$account);
                        $this->display('FindPassword/find_verify');
                    }
                    else
                    {
                        if (preg_match("/^([A-Za-z0-9])([\w\-\.])*@(vip\.)?([\w\-])+(\.)(com|com\.cn|net|cn|net\.cn|org|biz|info|gov|gov\.cn|edu|edu\.cn|biz|cc|tv|me|co|so|tel|mobi|asia|pw|la|tm)$/",$account))
                        {
                        	session('email',$account);
                            list($name, $server) = explode('@', $account);
                            $emailurl = 'http://mail.'.$server;
                            $this->sendmail();
                            $this->assign('emailurl',$emailurl);
                            $this->assign('bindEmail',$account);
                            $this->display('FindPassword/email');
                        }
                        else
                        {
                            $content = "抱歉，您输入的账号错误";
                            $this->assign('content',$content);
                            $this->display('FindPassword/find_username');
                        }
                    }
                }
            }
        }
    }
    public function sureaccount()
    {
        $code = I('post.verifytext');
        $codeed = session('code');
        if($code == $codeed && !empty($codeed) )
        {
            $lasturl = I('post.redirect') ? I('post.redirect') : I('get.redirect');
            $this->assign('lasturl',$lasturl);
            $this->display('FindPassword/set_pw');
        }
        else
        {
            header('HTTP/1.1 404 Not Found');
            exit;
        }
    }

    //获取手机验证码,并发送到用户手机上
    public function getcode()
    {
        $phone = I('post.phoneVal');
        $m = new Memcache();
        $m->addServer('localhost', 11211);
        $numbers = $m->get($phone);
        if ($numbers)
        {
            if ($numbers < 5)
            {
                $code =  rand(100000,999999);
                session('code',$code);
                $msg = "尊敬的用户，您的手机验证码是".$code."，如果超过时间请重新获取，谢谢合作。";
                echo "true";
                $time = time();
                session('time',$time);
                sendPhoneMsg($phone,$msg);
                $m->increment($phone,1);
            }
            else
            {
                $sign = 1;
                $this->assign('sign'.$sign);
                $this->display('FindPassword/error');
            }
        }
        else
        {
            $m->set($phone, 1, 0, 84600);
            $code =  rand(100000,999999);
            session('code',$code);
            $msg = "尊敬的用户，您的手机验证码是".$code."，如果超过时间请重新获取，谢谢合作。";
            echo "true";
            $time = time();
            session('time',$time);
            sendPhoneMsg($phone,$msg);
        }
    }
    //发送邮件
    public function sendmail()
    {
        $usermail = session('email');
        $account = session('account');
        if (S($account))
        {
            $emailsign = 1;
            $content = "一个小时内只能发送一次重置密码邮件，请到";
            $this->assign('content',$content);
            $this->assign('emailsign',$emailsign);
        }
        else {
            $code = substr(uniqid(rand()), -6);
            $token = md5($account . ':' . $code);
            S($account, $code, 3600);
            $time = time();
            $appurl = DOMAIN.'FindPassword-suremail-' . $time . '-' . $token;
            $prompt = '尊敬的用户您好，请您在一个小时内点击链接，如有超时或者错误请重新获取'."\t";
            $url = $prompt.'<a href="'.DOMAIN.'FindPassword-suremail-code-' . $account . '-token-' . $token . '">' . $appurl . '</a>';
            think_send_mail($usermail, '智通人才', '找回密码', $url);
            $content = "重置密码邮件已发送到";
            $this->assign('content',$content);
        }
    }

    public function suremail()
    {
        $account = I('get.code');
        $tokened = I('get.token');
        $code = S($account);
        if (!empty($code))
        {
            session('account',$account);
            $tokening = md5($account.':'.$code);
            if ($tokened == $tokening)
            {
                $this->display('FindPassword/set_pw');
            }
            else
            {
                $content = '对不起，您的链接超过有效时间，请重新获取！';
                $this->assign('content',$content);
                $this->display('FindPassword/error');
            }
        }
        else
        {
            $content = '对不起您已超时！！！';
            $this->assign('content',$content);
            $this->display('FindPassword/error');
        }
    }

    public function setpassword()
    {
        $redirect = I('get.str')?I('get.str'):I('param.redirect');
        $redirect = urlencode($redirect);
        $url = APP_DOMIN.'Chitone-Account-verifyAccount';
        $account = session('account');
        if (!empty($account))
        {
            $date['user_account'] = $account;
        }
        $date['ip'] = get_client_ip();
        $date['user_type'] = 0;
        $res = urlencode(json_encode($date));
        $result = _get($url,$res);
        $account_id = $result['result']['account_id'];
        $per_user_id = $result['result']['per_user_id'];
        $password = I('post.password');
        $account = session('account');
        if (!empty($account_id))
        {
            $arr['account_id'] = (int)$account_id;
        }
        if (!empty($per_user_id))
        {
            $arr['per_user_id'] = (int)$per_user_id;
        }
        if (!empty($password))
        {
            $arr['password'] = $password;
        }
        if (!empty($account))
        {
            $arr['account'] = $account;
        }
        $jsondate = urlencode(json_encode($arr));
        $appurl = APP_DOMIN.'Chitone-AccountUser-modifyUser';
        $appresult = _get($appurl,$jsondate);

        if ($redirect)
        {
            redirect('Account-login?redirect='.$redirect, 0, '页面跳转中...');
        }
        else
        {
            redirect('Account-login',0, '页面跳转中...');
        }

    }


}