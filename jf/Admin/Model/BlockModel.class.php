<?php
namespace Admin\Model;
use Think\Model;
class BlockModel extends Model {
	//自动验证
	protected $_validate = array(     
		array('title','require','标题必须填写'), //默认情况下用正则进行验证   
        array('mark','require','标识串必须填写'),
        array('mark','','该标识串已经存在',0,'unique',3),
        array('type','require','类型必须选择'), 
	);
}