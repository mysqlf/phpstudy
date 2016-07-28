<?php
/**
 * EmptyAction类
 *
 * @author RohoChan<[email]rohochan@gmail.com[/email]>
 * @version $Id$
 * @copyright `echo TM_ORGANIZATION_NAME`, `date +"%e %B, %Y" | sed 's/^ //'`
 * @package default
 **/

class EmptyAction extends Action {
	//访问不存在的function时进行404跳转
    function _empty(){ 
		header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 
		$this->display("Public:404"); 
    } 
    
	public function index(){
		//根据当前模块名来判断要执行的操作
		/*$moduleName = MODULE_NAME;
		$this->show($moduleName.'模块不存在');*/
		header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 
		$this->display("Public:404"); 
    }
}