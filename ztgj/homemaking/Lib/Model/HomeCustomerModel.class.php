<?php
	class HomeCustomerModel extends RelationModel{
		protected $connection = 'DB_CONFIG3';
        protected $trueTableName = 'HomeCustomer';
		//protected $dbName = 'mak_businessT.dbo';
		
		/*ThinkPHP可以很轻松的完成数据表的关联CURD操作，目前支持的关联关系包括下面四种：
		HAS_ONE、BELONGS_TO、HAS_MANY和MANY_TO_MANY。*/
		protected $_link = array(
		    'homemaking_community'  =>  array(
		        'mapping_type'    	=>HAS_MANY,
                //'class_name'    	=>'homemaking_community',
	          	//'mapping_name'		=>'homemaking_community',
	            'foreign_key'		=>'fldPersonId'
		    )
//		    '关联2'  =>  array(
//		        '关联属性1' => '定义',
//		        '关联属性N' => '定义',
//		    ),
//		    '关联3'  =>  HAS_ONE, // 快捷定义
		);
	}