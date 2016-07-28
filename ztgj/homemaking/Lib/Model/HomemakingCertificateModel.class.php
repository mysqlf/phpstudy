<?php
	class HomemakingCertificateModel extends RelationModel{
		protected $connection = 'DB_CONFIG5';
        //protected $trueTableName = 'homemaking_sitter';
		//protected $dbName = 'mak_businessT.dbo';
		
		protected $fields = array(
        	'fldId','fldName','fldStatus','fldType','fldCreateDate','fldOwner','lastEditdt','lastEditby','_pk' => 'fldId','_autoinc' => true
    	);
		
		/*ThinkPHP可以很轻松的完成数据表的关联CURD操作，目前支持的关联关系包括下面四种：
		HAS_ONE、BELONGS_TO、HAS_MANY和MANY_TO_MANY。*/
	/*	protected $_link = array(
		    'homemaking_sitter'  =>  array(
		        'mapping_type'    	=>MANY_TO_MANY,
                //'class_name'    	=>'homemaking_sitter',
	          	//'mapping_name'		=>'homemaking_sitter',
	            'foreign_key'		=>'fldCertificateId',//中间表的字段
	            'relation_foreign_key'=>'fldSitterId',//中间表的字段
	            'relation_table'=>'homemaking_sitter_certificate'
		    )
//		    '关联2'  =>  array(
//		        '关联属性1' => '定义',
//		        '关联属性N' => '定义',
//		    ),
//		    '关联3'  =>  HAS_ONE, // 快捷定义
		);*/
	}