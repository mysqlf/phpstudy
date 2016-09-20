<?php
namespace Admin\Model;
use Think\Model;
class TradeModel extends Model {
	//自动验证
	protected $_validate = array(
        array('uid','require','用户不存在'),     
		array('date','require','进退货日期不能为空'), //默认情况下用正则进行验证   
        array('mark','require','进退货状态必须选择'),
        array('product','require','产品名称和规格不能为空'),
        array('score','require','积分不能为空'),
	);
}