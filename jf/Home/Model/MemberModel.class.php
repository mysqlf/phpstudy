<?php
namespace Home\Model;
use Think\Model;
class MemberModel extends Model {
	//自动验证
	protected $_validate = array(
		array('username','require','用户名必须填写'), //默认情况下用正则进行验证
		array('username','','该用户名已经被占用，请换其他手机号码',0,'unique',3),
		array('phone','require','手机号码必须填写'), //默认情况下用正则进行验证
		array('phone','','该手机号码已经被占用，请换其他手机号码',0,'unique',3), // 在新增的时候验证name字段是否唯一
        array('email','','该邮箱已经被占用，请换其他邮箱',0,'unique',3), // 在新增的时候验证name字段是否唯一
        array('address','require','邮寄地址必须填写'), //默认情况下用正则进行验证   
		array('password','6,16','密码为6到16个字符',2,'length'),
		array('password','require','密码必须填写',2),
		array('password2','password','确认密码不正确',2,'confirm'), // 验证确认密码是否和密码一致
	);
	//自动完成
	protected $_auto = array (
		array('password','md5',3,'function') , // 对password字段在新增的时候使md5函数处理
	);
}