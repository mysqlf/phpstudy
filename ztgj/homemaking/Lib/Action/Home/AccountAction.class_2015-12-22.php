<?php
// 本类由系统自动生成，仅供测试用途
class AccountAction extends Action 
{

	//获取用户的openid并且判断数据库中是不是有这个数据，有的话跳转到热门兼职，没有的话跳到注册页面
    public function index()
    {
        $lasturl = I('get.str')?I('get.str'):I('get.redirect');
        $this->assign('lasturl',$lasturl);
        $redirect = urlencode($lasturl);
        $this->assign('redirect',$redirect);
        if(session('openid'))
        {
            $openid = session('openid');
        }
        else
        {
            $code = I('get.code',0);
            $openid = _openid($code);
            session('openid',$openid);
        }
        $account_info = D('AccountInfo');
        $uid = $account_info->logining();
        if ($uid)
        {
            if ($lasturl)
            {
                redirect($lasturl, 0, '页面跳转中...');
            }
            else
            {
                redirect('Homemaking-index-t-motherBabyQA-v'.time(), 0, '页面跳转中...');
            }
         }
        else {
            $time = session('time');
            $newtime = time();
            $losetime = $newtime - $time;
            $losetime = 120 - $losetime;
            if ($losetime > 1 && $losetime < 120) {
                $this->assign('time', $losetime);
            }
            $this->display('Account:register');
        }
    }

    private function writeLog($str)
    {

        $fp = fopen('Runtime/Logs/log.txt','a+');
        fwrite($fp, $str);
        fclose($fp);
    }
    //用户注册
    public function reg()
	{
        $redirect = I('post.redirect')?I('post.redirect'):I('post.str');
        $redirect = urlencode($redirect);
        $phone = I('post.phoneVal');
        $password = I('post.password');
        $openid = session('openid');
        $userinfo = userinfo($openid);
        $username = $userinfo['nickname'];
        $headimgurl = $userinfo['headimgurl'];
        $telphone['user_telphone'] = $phone;
        $user['open_id'] = $openid;
        if (!empty($phone))
        {
            $user['user_account'] = $phone;
        }
        if (!empty($password))
        {
            $user['user_password'] = $password;
        }
        $user['ip'] = get_client_ip();
        $user['open_name'] = $username;
        $user['type'] ='weixin';
        $user['role_type'] = '0';
        $user['account_from'] = '3001';
        $data = urlencode(json_encode($user));
        $url =APP_DOMIN;
        $url = $url.'Chitone-Account-reg';
        $res = _get($url,$data);
        $uid = $res['result']['account_id'];
        session('uid',$uid);
        
        //推广二维码注册统计 
        if($res['result']['account_id']){
            $this->_regCount();
        }
        
        $code = $res['result']['code'];
        $redis = new Redis();
        $redis->connect('192.168.2.183', '6379');
                $redis->auth('job5156RedisMaster183');
        $redisresult = $redis->HGetAll($code);
        $per_user_id = $redisresult['id'];
        session('per_user_id',$per_user_id);
        cookie('per',$code,86400);
        $atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
        $attdate['per_user_id'] = $per_user_id;
        $userlocation = S($openid);
        $userlocation = json_decode($userlocation, true);
        $place = $this->_planeCrood($userlocation);
        session('place', $place);

        $attdate['attach']['user_x'] = $place['x'];
        $attdate['attach']['user_y'] = $place['y'];
        $attdate['attach']['head_img_url'] = $headimgurl;
        $attres = urlencode(json_encode($attdate));
        $attresult = _get($atturl,$attres);
        
        $resume['per_user_id'] = $per_user_id;
        $resume['resume_type'] = 4;
        $resume['resume_name'] = "我的微名片";
        $resumedata = urlencode(json_encode($resume));
        $resumeurl = APP_DOMIN.'Chitone-AccountUser-modifyResume';
        $resumeresult = _get($resumeurl,$resumedata);

        if ($per_user_id)
        {
            $app_url = APP_URL.'api/weixinwaibao/per/regist.json';
            $appdate['mobile'] = $phone;
            $appdate['perUserId'] = $per_user_id;
            $appdate['password'] = $password;
            $result = _get($app_url,$appdate);
            redirect('Account-baseshow?redirect=' . $redirect);
        }
        else
        {
            redirect('Account-index?redirect=' . $redirect);
        }
    }
    public function baseshow()
    {
        $lasturl = I('get.str') ? I('get.str') : I('get.redirect');
        $this->assign('lasturl',$lasturl);

        $userinfourl = APP_DOMIN.'Chitone-AccountUser-userInfo';
        $userinfodate['per_user_id'] = session('per_user_id');
        $userinfodate['field'] = 'id,user_name,gender,location,birthday,jobyear_type,job_state,degree';
        $userdata = urlencode(json_encode($userinfodate));
        $userinforesult = _get($userinfourl,$userdata);

        $m = new Memcache();
        $m->addServer('192.168.2.23', 11211);
        $location = $m->get('ptimeZoning');

        $alllocation = $userinforesult['result']['result'][0]['location'];
        $prolocation = (int)($alllocation/1000000)*1000000;
        $citylocation  = (int)($alllocation/10000)*10000;
        if ($prolocation == $citylocation)
        {
            $citylocation  = (int)($alllocation/100)*100;
        }
        if ($prolocation == $citylocation)
        {
            $userinforesult['result']['result'][0]['citylocation'] = trim( $location[$citylocation]['name'],"\"");
        }
        else
        {
            $userinforesult['result']['result'][0]['prolocation'] = trim( $location[$prolocation]['name'],"\"");
            $userinforesult['result']['result'][0]['citylocation'] = $userinforesult['result']['result'][0]['prolocation'].trim( $location[$citylocation]['name'],"\"");
        }

        $appurl = APP_DOMIN.'Chitone-AccountUser-attachInfo';
        $date['per_user_id'] = session('per_user_id');
        $date['field'] = 'recent_job';
        $date['limit'] = 1;
        $appdata = urlencode(json_encode($date));
        $appresult = _get($appurl,$appdata);

        $userinforesult['result']['result'][0]['recent_job'] = $appresult['result']['result'][0]['recent_job'];

        $userinforesult =   $userinforesult['result']['result'][0];
        if ($userinforesult['gender'] == 2)
        {
            $userinforesult['genderman'] = "check";
            $userinforesult['genderwoman'] = "checked";
        }
        else
        {
            $userinforesult['genderman'] = "checked";
            $userinforesult['genderwoman'] = "check";
        }
        switch($userinforesult['degree'])
        {
            case 1:
                $userinforesult['degreechar'] = "初中";
                break;
            case 2:
                $userinforesult['degreechar'] = "高中";
                break;
            case 3:
                $userinforesult['degreechar'] = "中专";
                break;
            case 4:
                $userinforesult['degreechar'] = "大专";
                break;
            case 5:
                $userinforesult['degreechar'] = "本科";
                break;
            case 6:
                $userinforesult['degreechar'] = "硕士";
                break;
            case 7:
                $userinforesult['degreechar'] = "MBA";
                break;
            case 8:
                $userinforesult['degreechar'] = "博士";
                break;
        }
        switch( $userinforesult['job_state'])
        {
            case 0:
                $userinforesult['job_statechar'] = "目前正在找工作";
                break;
            case 1:
                $userinforesult['job_statechar'] = "半年内无换工作的计划";
                break;
            case 2:
                $userinforesult['job_statechar'] = "一年内无换工作的计划";
                break;
            case 3:
                $userinforesult['job_statechar'] = "观望好的机会再考虑";
                break;
            case 4:
                $userinforesult['job_statechar'] = "我暂时不想找工作";
                break;
        }
        if(!empty($userinforesult['jobyear_type']))
        {
            switch( $userinforesult['jobyear_type'])
            {
                case -1:
                    $userinforesult['jobyear_typechar'] = "在读学生";
                    break;
                case 0:
                    $userinforesult['jobyear_typechar'] = "应届毕业生";
                    break;
                case 11:
                    $userinforesult['jobyear_typechar'] = "10年以上";
                    break;
                default:
                    $userinforesult['jobyear_typechar'] =  $userinforesult['jobyear_type']."年";
                    break;
            }
        }
        
        //注册简历统计
        //if (!$userinforesult['recent_job'])
        if (!($userinforesult['user_name'] || $userinforesult['gender'] || $userinforesult['location'] || $userinforesult['birthday'] || $userinforesult['jobyear_type'] || $userinforesult['degree'] || $userinforesult['recent_job']))
        {
        	session('regResumeCount',true);
        }
        
        $this->assign('result', $userinforesult);
        $this->display('Account:basic_mes');
    }


    public function base()
    {
        $redirect = I('post.redirect')?I('post.redirect'):I('post.str');
         $user_name = I('post.user_name');
         $gender = I('post.gender');
         $birthday = I('post.birthday');
         $location = I('post.location');
         $degree = I('post.degree');
         $job_state = I('post.job_state');
         $jobyear_type = I('post.jobyear_type');
         $recent_job = I('post.recent_job');
         $datetime = time();
        if (!empty($user_name))
        {
            $arr['user_name'] = $user_name;
            $appdate['username'] = $user_name;
        }
        if (!empty($gender))
        {
            $arr['gender'] = $gender;
            if ($gender == 2)
            {
                $appdate['sex'] = 0;
            }
            else
            {
                $appdate['sex'] = $gender;
            }
        }
        if (!empty($birthday))
        {
            $arr['birthday'] = $birthday;
            $userdate = date("Y",strtotime($birthday));
            $nowdate = date("Y",time());
            $appdate['age'] = $nowdate-$userdate;
        }
        if (!empty($location))
        {
            $arr['location'] = $location;
            $appdate['locationC'] = $location;
        }
        if (!empty($degree))
        {
            $arr['degree'] = $degree;
            $appdate['degree'] = $degree;
        }

        $arr['job_state'] = $job_state;

        if (!empty($jobyear_type))
        {
            $arr['jobyear_type'] = $jobyear_type;
        }

        $attdate['attach']['recent_job'] = $recent_job;
        $arr['public_settings'] = 1;
        $atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
        
        $openid =session('openid');
        /*$userlocation = S($openid);
        $userlocation = json_decode($userlocation, true);
        $place = $this->_planeCrood($userlocation);
        session('place', $place);
        $attdate['attach']['user_x'] = $place['x'];
        $attdate['attach']['user_y'] = $place['y'];*/
        
        $attdate['per_user_id'] = session('per_user_id');
        $attachdata = urlencode(json_encode($attdate));
        $attresult = _get($atturl,$attachdata);

        $arr['per_user_id'] = session('per_user_id');
        $arr['account_id'] = session('uid');
        $url = APP_DOMIN.'Chitone-AccountUser-modifyUser';
        $data = urlencode(json_encode($arr));
        $res = _get($url,$data);
        $code = $res['result']['code'];
        cookie('per',$code,86400);
        $appdate['perUserId'] = session('per_user_id');
        $appdate['openId'] = session('openid');
        $appurl = APP_URL.'api/weixinwaibao/per/saveBaseData.json';
        $result = _get($appurl,$appdate);
        
		/*$archives_person_resume = D('ArchivesPersonResume');
    	//是否存在微名片,不存在即进行统计
        $archives_person_resume_temp = clone($archives_person_resume);
        $archivesPersonResumeTempResult = $archives_person_resume_temp->getResumeItem(array('account_id'=>session('uid'),'resume_type'=>4));
        if(!$archivesPersonResumeTempResult['id']){
            $this->_regResumeCount();
        }*/
        if(true == session('regResumeCount')){
            $this->_regResumeCount();
        	session('regResumeCount',null);
        }
            if ($redirect)
            {
                redirect($redirect);
            }
            else
            {
                redirect('Homemaking-index-t-motherBabyQA'."-{$datetime}-{$datetime}",0, '页面跳转中...');
            }
    }

    private function getphoto($uid,$headimgurl)
    {
        $url = $headimgurl;
        $filename = mkFilePath($uid,'Uploads/Users/','photo');
        $filename = $filename.'userphoto.jpg';
        $hander = curl_init();
        $fp = fopen($filename,'wb');
        curl_setopt($hander,CURLOPT_URL,$url);
        curl_setopt($hander,CURLOPT_FILE,$fp);
        curl_setopt($hander,CURLOPT_HEADER,0);
        curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
        //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
        curl_setopt($hander,CURLOPT_TIMEOUT,60);
        curl_exec($hander);
        curl_close($hander);
        fclose($fp);
    }

    //验证账号是否存在
    public function telphone()
    {
        $phone['user_account'] = I('post.phoneVal');
        //$account_info = D('AccountInfo');
        //$res = $account_info->accountAssign($phone);
        //$res =  $account_info->where($phone)->find();
        $url =APP_DOMIN;
        $url = $url.'Chitone-Account-allowReg';
        $data =  urlencode(json_encode($phone));
        $res = _get($url,$data);
        $status = $res['status'];
        if(IS_AJAX)
        {
        	//$status为0表示用户不存在可以使用，200000表示用户存在
            if ($status)
            {
                echo "false";
                exit();
            }
            else
            {
                echo "true";
                exit();
            }
        }
    }
    //验证code及手机验证码是不是和session中的一样
    public function code()
    {
        $getcode = I('post.codeVal');
        $code = session('code');
        $phoneVal= I('post.phoneVal');
        $phone = session('phone');
        if(IS_AJAX)
        {	
        	$flag = true;
	        if (session('time')){
	            $time = session('time');
	            $newtime = time();
	            $losetime = $newtime - $time;
	            $losetime = 10*60 - $losetime;
	            if ($losetime > 1 && $losetime < 10*60) {
	            	$flag = true;
	            }else{
	            	$flag = false;
	            	session('code',null);
	            }
	        }
            if(($getcode==$code)&&($phoneVal==$phone)&&$flag)
            {
                echo "true";
                exit();
            }
            else
            {
                echo "false";
                exit();
            }
        }
    }
    //获取手机验证码,并发送到用户手机上
    public function getcode()
    {
        $phone = I('post.phoneVal');
        /*if (!session('phone'))
        {
            session('phone',$phone);
        }*/
        session('phone',$phone);
        $m = new Memcache();
        $m->addServer('localhost', 11211);
        $numbers = $m->get($phone);
        if ($numbers)
        {
            if ($numbers < 50)
            {
                $code =  rand(100000,999999);
                session('code',$code);
                $msg = "【国民妈妈】您的验证码为：".$code."，本验证码10分钟内有效，如非本人操作请忽略本短信。";
                echo "true";
                $time = time();
                session('time',$time);
                sendPhoneMsg($phone,$msg);
                $m->increment($phone,1);
            }
            else
            {
                $content = 1;
                echo $content;
                //$this->assign('content'.$content);
                //$this->display('feasy/error');
            }
        }
        else
        {
            $m->set($phone, 1, 0, 84600);
            $code =  rand(100000,999999);
            session('code',$code);
            $msg = "【国民妈妈】您的验证码为：".$code."，本验证码10分钟内有效，如非本人操作请忽略本短信。";
            echo "true";
            $time = time();
            session('time',$time);
            sendPhoneMsg($phone,$msg);
        }
        /*$phone = I('post.phone');
        $code =  rand(100000,999999);
        session('phone',$phone);
        //session(array('name'=>'code','expire'=>30));
        session('code',$code);
        //$msg = "尊敬的用户，您的手机验证码是".$code."，如果超过时间请重新获取，谢谢合作。";
        $msg = "【国民妈妈】您的验证码为：".$code."，本验证码10分钟内有效，如非本人操作请忽略本短信。";
        echo "true";
        $time = time();
        session('time',$time);
        sendPhoneMsg($phone,$msg);*/
    }

	//用户登录
	public function login()
	{
        $hign = '0';
        $lasturl = I('get.str')?I('get.str'):I('get.redirect');
        $this->assign('lasturl',$lasturl);
        $redirect = urlencode($lasturl);
        $this->assign('redirect',$redirect);
        if(session('openid'))
        {
            $openid = session('openid');
        }
        else
        {
            $code = I('get.code',0);
            $openid = _openid($code);
            session('openid',$openid);
        }
        $account_info = D('AccountInfo');
        $uid = $account_info->logining();

        if ($uid)
        {
            if ($lasturl)
            {
            	if (session('requirement')) {
					redirect(U('Homemaking-requirementSubmit4Login','',''));
				}else {
                	redirect($lasturl, 0, '页面跳转中...');
				}
            }
            else
            {
            	if (session('requirement')) {
					redirect(U('Homemaking-requirementSubmit4Login','',''));
				}else {
                	redirect('Homemaking-index-t-motherBabyQA-v'.time(), 0, '页面跳转中...');
				}
            }
        }
        else
        {
            $this->assign('hign',$hign);
            $this->display('Account:login');
        }
	}

    public function getlogin()
    {
        $redirect = I('post.redirect') ? I('post.redirect') : I('post.str');
        $redirect = urlencode($redirect);
        $user_account = I('post.account');
        $password = I('post.password');
        $userinfo['user_account'] = $user_account;
        $userinfo['user_password'] =  $password;
        $userinfo['ip'] = get_client_ip();
        $userinfo['open_id'] = session('openid');
        $userinfo['type'] = 'weixin';
        $user = userinfo($userinfo['open_id']);
        $username = $user['nickname'];
        $headimgurl = $user['headimgurl'];
        $userinfo['open_name'] = $username;
        //$userinfo['account_from'] = '3001';
        //dump($userinfo);
        $data = urlencode(json_encode($userinfo));
        $url = APP_DOMIN.'Chitone-Account-login';
        $res = _get($url, $data);
        $uid = $res['result']['account_id'];
        session('uid', $uid);
        $code = $res['result']['code'];
        $redis = new Redis();
        $redis->connect('192.168.2.183', '6379');
                $redis->auth('job5156RedisMaster183');
        $redisres = $redis->HGetAll($code);
        $per_user_id = $redisres['id'];
        session('per_user_id', $per_user_id);
        cookie('per', $code, 86400);
        if ($per_user_id)
        {
            $atturl = APP_DOMIN.'Chitone-AccountUser-modifyUserAttach';
            $attdate['per_user_id'] = session('per_user_id');
            $attdate['attach']['head_img_url'] = $headimgurl;

            /*$userlocation = S('openid');
            $userlocation = json_decode($userlocation, true);
            $place = $this->_planeCrood($userlocation);
            session('place', $place);

            $attdate['attach']['user_x'] = $place['x'];
            $attdate['attach']['user_y'] = $place['y'];*/
            $attres =  urlencode(json_encode($attdate));
            $attresult = _get($atturl,$attres);

            $logindate['account'] = $user_account;
            $loginres = urlencode(json_encode($logindate));
            $loginurl =APP_DOMIN.'Chitone-Account-verifyStatus';
            $loginresult = _get($loginurl,$loginres);
            if ($loginresult['result']['bindMobile'])
            {
                $login_url = APP_URL.'api/weixinwaibao/per/saveOrUpdate.json';
                $login_date['mobile'] = $loginresult['result']['bindMobile'];
                $login_date['perUserId'] = $per_user_id;
                $login_date['password'] = $password;
                $appdate['account'] = $loginresult['result']['bindMobile'];
                $result = _get($login_url,$login_date);
            }
            else
            {
                $login_url = APP_URL.'api/weixinwaibao/per/saveOrUpdate.json';
                $login_date['mobile'] = $user_account;
                $login_date['perUserId'] = $per_user_id;
                $login_date['password'] = $password;
                $appdate['account'] = $user_account;
                $result = _get($login_url,$login_date);
            }
			
            if ($redirect)
            {
                $isbindurl = APP_DOMIN . 'Chitone-Account-isBindMobile';
                $isbindinfo['open_id'] = session('openid');
                $isbindinfo['type'] = 'weixin';
                $idbinddata = urlencode(json_encode($isbindinfo));
                $result = _get($isbindurl, $idbinddata);
                $status = $result['status'];
                if ($status)
                {
                	//提交预约
					if (session('requirement')) {
						/*$homemaking = A("Homemaking");
						$homemaking->requirementSubmit4Login();*/
						redirect(U('Homemaking-requirementSubmit4Login','',''));
					}else {
                		redirect($redirect);
					}
                    /*$userinfourl = APP_DOMIN.'Chitone-AccountUser-userInfo';
                    $userinfodate['per_user_id'] = session('per_user_id');
                    $userinfodate['field'] = 'id,user_name,gender,location,birthday,jobyear_type,job_state,degree';
                    $userdata = urlencode(json_encode($userinfodate));
                    $userinforesult = _get($userinfourl,$userdata);
                    $userinforesult = $userinforesult['result']['result'];

                    $appurl = APP_DOMIN.'Chitone-AccountUser-attachInfo';
                    $infodate['per_user_id'] = session('per_user_id');
                    $infodate['field'] = 'recent_job';
                    $infodate['limit'] = 1;
                    $appdata = urlencode(json_encode($infodate));
                    $appresult = _get($appurl,$appdata);
                    $appresult = $appresult['result']['result'];

                    if($appresult['recent_job'] && $userinforesult['user_name'] && $userinforesult['gender'] && $userinforesult['location'] && $userinforesult['birthday'] && $userinforesult['degree'] && $userinforesult['job_state'] && $userinforesult['jobyear_type'])
                    {
                        redirect($redirect);
                    }
                    else
                    {
                        redirect('Account-baseshow?redirect=' . $redirect, 0, '页面跳转中...');
                    }*/

                }
                else
                {
                    redirect('Account-bind?redirect=' . $redirect, 0, '页面跳转中...');
                }
            }
            else
            {
            	//提交预约
				if (session('requirement')) {
					redirect(U('Homemaking-requirementSubmit4Login','',''));
				}else {
                	redirect('Homemaking-index-t-motherBabyQA-v'.time(), 0, '页面跳转中...');
				}
            }
        }
        else
        {
            $hign = '1';
            $this->assign('hign', $hign);
            $this->assign('redirect', $redirect);
            $this->display('Account:login');
        }
    }

    public function bind()
    {
        $lasturl = I('get.str') ? I('get.str'):I('get.redirect');
        $this->assign('lasturl',$lasturl);
        if (session('time'))
        {
            $time = session('time');
            $newtime = time();
            $losetime = $newtime - $time;
            $losetime = 120 - $losetime;
            if ($losetime > 1 && $losetime < 120) {
                $this->assign('time', $losetime);
            }
            $this->display('Account:bindtel');
        }
   else
   {
       $this->display('Account:bindtel');
   }
    }
    //验证手机号码
    public function mobile()
    {
        $phone['user_telphone'] = I('post.phoneVal');
        //$account_info = D('AccountInfo');
        //$res = $account_info->accountAssign($phone);
        //$res =  $account_info->where($phone)->find();
        $url =APP_DOMIN;
        $url = $url.'Chitone-Account-allowBindMobile';
        $data =  urlencode(json_encode($phone));
        $res =_get($url,$data);
        //$status为200013时候是手机用了，0表示手机没有被占用。
        $status = $res['status'];
        if(IS_AJAX)
        {
            if ($status)
            {
                echo "false";
                exit();
            }
            else
            {
                echo "true";
                exit();
            }
        }
    }

//提交手机到数据库
    public function bindphone()
    {
        $redirect = I('post.redirect');
        $redirect = urlencode($redirect);
        $phone['open_id'] = session('openid');
        $phone['user_telphone'] = I('post.phoneVal');
        $phone['type'] ='weixin';
        $url =APP_DOMIN;
        $url = $url.'Chitone-Account-bindMobile';
        //$url = 'localhost/Chitone-Account-bindMobile';
        $data =  urlencode(json_encode($phone));
        $res = _get($url,$data);
        $status = $res['status'];
        $login_url = APP_URL.'api/weixinwaibao/per/saveOrUpdate.json';
        $login_date['mobile'] = $phone['user_telphone'];
        $login_date['perUserId'] = session('per_user_id');
        $appdate['account'] =  $phone['user_telphone'];
        $result = _get($login_url,$login_date);
        if($status)
        {
        	//提交预约
			if (session('requirement')) {
				redirect(U('Homemaking-requirementSubmit4Login','',''));
			}else {
            	redirect('Account-bind', 0, '页面跳转中...');
			}
        }
        else
        {
            $userinfourl = APP_DOMIN.'Chitone-AccountUser-userInfo';
            $userinfodate['per_user_id'] = session('per_user_id');
            $userinfodate['field'] = 'id,user_name,gender,location,birthday,jobyear_type,job_state,degree';
            $userdata = urlencode(json_encode($userinfodate));
            $userinforesult = _get($userinfourl,$userdata);
            $userinforesult = $userinforesult['result']['result'][0];

            $appurl = APP_DOMIN.'Chitone-AccountUser-attachInfo';
            $date['per_user_id'] = session('per_user_id');
            $date['field'] = 'recent_job';
            $date['limit'] = 1;
            $appdata = urlencode(json_encode($date));
            $appresult = _get($appurl,$appdata);
            $appresult = $appresult['result']['result'][0];

            if($appresult['recent_job'] && $userinforesult['user_name'] && $userinforesult['gender']  && $userinforesult['location'] && $userinforesult['birthday'] && $userinforesult['degree'] && $userinforesult['job_state'] && $userinforesult['jobyear_type'])
            {
                if ($redirect)
                {
                	//提交预约
					if (session('requirement')) {
						redirect(U('Homemaking-requirementSubmit4Login','',''));
					}else {
                    	redirect($redirect);
					}
                }
                else
                {
                	//提交预约
					if (session('requirement')) {
						redirect(U('Homemaking-requirementSubmit4Login','',''));
					}else {
                    	redirect('Homemaking-index-t-motherBabyQA');
					}
                }

            }
            else
            {
            	//提交预约
				if (session('requirement')) {
					redirect(U('Homemaking-requirementSubmit4Login','',''));
				}else {
                	//redirect('Account-baseshow?redirect=' . $redirect, 0, '页面跳转中...');
                	redirect(U('Homemaking-index-t-motherBabyQA',array('v'=>time()),''));
				}
            }
        }
    }

    /**
     * 根据用户平面坐标
     * @param  [array] $array 经纬度数组
     * @return string
     */
    private function _planeCrood ($array)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL , "http://api.map.baidu.com/geoconv/v1/?coords={$array['lng']},{$array['lat']}&from=5&to=6&ak=RvufqHb1h9WY4qwhBmGWs2Wv");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $json = json_decode(curl_exec($ch),true);
        curl_close($ch);
        $array = array('x'=>0,'y'=>0);
        if ($json['result'])
        {
            $array['x'] = $json['result'][0]['x'];
            $array['y'] = $json['result'][0]['y'];
        }
        return $array;
    }
    
	/**
     * 二维码推广 注册数统计
     * @return [type] [description]
     */
    private function _regCount(){
        $openid = session('openid');
        $record = M('ProjPtimeWechatRecord','',DB_CONFIG2);
        $where['openid'] = $openid;
        $where['active_type'] = 2;
        $res = $record->where($where)->find();
        
        if(isset($res['barcode_id']) && $res['barcode_id']){
            $data['barcode_id'] = $res['barcode_id'];
            $m = M('ProjPtimeWechatCount','',DB_CONFIG2);
            
            $m->where($data)->setInc('reg_count' , 1);
            $array = array();
            $array['openid'] = $openid;
            $array['barcode_id'] = $res['barcode_id'];
            $array['active_type'] = 2;
            $record->add($array);
        }
    }
    
    /**
     * 二维码推广微名片生成统计
     * @return [type] [description]
     */
    private function _regResumeCount(){
        $openid = session('openid');
        $record = M('ProjPtimeWechatRecord','',DB_CONFIG2);
        $where['openid'] = $openid;
        $res = $record->where($where)->find();

        if(isset($res['barcode_id']) && $res['barcode_id']){
            $data['barcode_id'] = $res['barcode_id'];
            $m = M('ProjPtimeWechatCount','',DB_CONFIG2);

            $m->where($data)->setInc('reg_resume_count' , 1);
        }  
    }
    
    /**
     * 获取accesstoken接口
     * @return void
     */
    public function getaccesstoken()
    {
        $accesstoken = accesstoken();
        $accesstoken = json_encode($accesstoken);
        die($accesstoken);
    }
}