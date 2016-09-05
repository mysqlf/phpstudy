<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
	//自动验证
	protected $_validate = array(     
		array('username','require','用户名必须填写'), //默认情况下用正则进行验证
		array('username','','该用户名已经存在，请换一个！',0,'unique',1), // 在新增的时候验证name字段是否唯一
		array('password','6,32','密码为6到32个字符',2,'length'),
		array('password','require','密码必须填写',2),
		array('password2','password','确认密码不正确',2,'confirm'), // 验证确认密码是否和密码一致
	);
}