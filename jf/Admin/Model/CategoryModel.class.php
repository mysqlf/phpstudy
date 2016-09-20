<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model {
	//自动验证
	protected $_validate = array(     
		array('pid','require','父类ID不存在'), //默认情况下用正则进行验证   
        array('mid','require','所属模型不能为空'), 
        array('name','require','分类名称不能为空'), 
	);
}