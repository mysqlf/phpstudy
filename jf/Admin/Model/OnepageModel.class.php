<?php
namespace Admin\Model;
use Think\Model;
class OnepageModel extends Model {
	//自动验证
	protected $_validate = array(     
		array('pid','require','父类ID不存在'), //默认情况下用正则进行验证   
        array('name','require','名称不能为空'), 
        array('cid','','该分类已经存在单页面',0,'unique',3),
	);
}