<?php
	class HomemakingSitterModel extends RelationModel{
		protected $connection = 'DB_CONFIG5';
        //protected $trueTableName = 'homemaking_sitter';
		//protected $dbName = 'mak_businessT.dbo';
		
		protected $fields = array(
        	'fldId','fldPersonId','fldType','fldSelfComment','fldCreateDate','fldOwner','lastEditdt','lastEditby','_pk' => 'fldId','_autoinc' => true,'_fk' => 'fldPersonId'
    	);
		
		/*ThinkPHP可以很轻松的完成数据表的关联CURD操作，目前支持的关联关系包括下面四种：
		HAS_ONE、BELONGS_TO、HAS_MANY和MANY_TO_MANY。*/
		protected $_link = array(
		    /*'homemaking_certificate'  =>  array(
		        'mapping_type'    	=>MANY_TO_MANY,
                'class_name'    	=>'HomemakingCertificate',
	          	//'mapping_name'		=>'homemaking_certificate',
	            'foreign_key'		=>'fldSitterId',//中间表的字段
	            'relation_foreign_key'=>'fldCertificateId',//中间表的字段
	            'relation_table'=>'homemaking_sitter_certificate'
		    )*/
		   /*	,'HomeInfo'  =>  array(
		        'mapping_type'    	=>BELONGS_TO,
                'class_name'    	=>'HomeInfo',
	          	//'mapping_name'		=>'HomeInfo',
	            'foreign_key'		=>'fldPersonId'
		    )*/
//		    '关联3'  =>  HAS_ONE, // 快捷定义
		);
	}