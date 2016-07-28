<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
    	//echo C('URL_PATHINFO_DEPR');
    	echo $_SERVER["REMOTE_ADDR"];
		$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    public function test10()
    {
    	$homeCustomer = D('HomeCustomer','',DB_CONFIG3);
	    $result = $homeCustomer->where(array('fldID'=>6))->find();
	    //echo $homeCustomer->getLastSQL()."<br/>";
	    echo $homeCustomer->getDbError();
	    dump($result);
    }
    public function test(){
    	header('content-type:text/html;charset=utf-8');
//    	echo $_SERVER["REMOTE_ADDR"];
		$test = S('test')?S('test'):(S('test','memcache',5)?S('test'):'false');
	    echo $test;
	    //echo S('test');
	    
    	$ptime_info = M('proj_ptime_info');
    	$result = $ptime_info->where(array('id'=>55))->find();
    	if($result){
    		dump($result);
    	}
    	
	    $account_info = M('account_info',null,DB_CONFIG2);
	    $result = $account_info->where(array('id'=>6))->find();
	    if($result){
    		dump($result);
    	}
	    
	    $Dao = D('HomeCustomer');
	    $result = $Dao->where(array('fldID'=>6))->find();
	    echo $Dao->getDbError();
	    dump($result);
	    
	    
	    $homeCustomer = D('HomeCustomer');
	    $result = $homeCustomer->where(array('fldID'=>6))->find();
	    //echo $homeCustomer->getLastSQL()."<br/>";
	    echo $homeCustomer->getDbError();
	    dump($result);
	    
	    
	    $homemaking_certificate = M('homemaking_certificate',null,"DB_CONFIG4");
	    $result = $homemaking_certificate->where(array('fldId'=>1))->find();
	    //echo $homemaking_certificate->getLastSQL()."<br/>";
	    echo $homemaking_certificate->getDbError();
	    dump($result);
	    
	    //echo $homemaking_certificate->db();
	    $result = $homeCustomer->query('SELECT * FROM HomeCustomer WHERE fldID = 6');
	    echo $homeCustomer->getDbError();
	    dump($result);
    	$this->show(" hello world!");
    }
    
    public function test2(){
    	$homemaking_certificate = M('homemaking_certificate',null,"DB_CONFIG5");
	    $result = $homemaking_certificate->where(array('fldId'=>1))->find();
	    echo $homemaking_certificate->getDbError();
	    dump($result);
    }
    
    public function test3(){
    	header("content-type:text/html;charset='utf-8'");
    	$HomeCustomer = D('HomeCustomer');
	    $result = $HomeCustomer->relation(true)->where(array('fldID'=>6))->find();
	    echo $HomeCustomer->getLastSQL()."<br/>";
	    //echo "<pre>";
	    echo $HomeCustomer->getDbError();
	    dump($result);
	    dump($result['homemaking_community']);
    }
    
    public function test4(){
    	header("content-type:text/html;charset='utf-8'");
    	$HomemakingSitter = D('HomemakingSitter');
	    $result = $HomemakingSitter->relation(true)->where(array('fldId'=>1))->find();
	    echo $HomemakingSitter->getLastSQL()."<br/>";
	    //echo "<pre>";
	    echo $HomemakingSitter->getDbError();
	    dump($result);
	    //dump($result['homemaking_sitter']);
    }
    
     public function test5(){
    	header("content-type:text/html;charset='utf-8'");
    	$HomemakingCertificate = D('HomemakingCertificate');
	    $result = $HomemakingCertificate->relation(true)->where(array('fldID'=>1))->find();
	    echo $HomemakingCertificate->getLastSQL()."<br/>";
	    //echo "<pre>";
	    echo $HomemakingCertificate->getDbError();
	    dump($result);
	    //dump($result['homemaking_sitter']);
    }
    
    public function hello(){
      /*../Public： 会被替换成当前项目的公共模板目录 通常是 /项目目录/Tpl/当前主题/Public/  
	    __TMPL__： 会替换成项目的模板目录 通常是 /项目目录/Tpl/当前主题/  
	    （注：为了部署安全考虑，../Public和__TMPL__不再建议使用）  
	    __PUBLIC__：会被替换成当前网站的公共目录 通常是 /Public/  
	    __ROOT__： 会替换成当前网站的地址（不含域名）   
	    __APP__： 会替换成当前项目的URL地址 （不含域名）  
	    __GROUP__：会替换成当前分组的URL地址 （不含域名）  
	    __URL__： 会替换成当前模块的URL地址（不含域名）  
	    __ACTION__：会替换成当前操作的URL地址 （不含域名）  
	    __SELF__： 会替换成当前的页面URL */
	    S('test','memcache',1);
	    $test = S('test');
	    echo $test;
	    
    	$hello = array('hello'=>'hello','world'=>'world!');
	    
	    S('test2',$hello);
	    $test = S('test2');
	    dump($test);
    	$this->assign('hello',$hello);
    	$this->display('hello');
    }
    
    function _empty(){ 
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 
        $this->display("Public:404"); 
    } 
}