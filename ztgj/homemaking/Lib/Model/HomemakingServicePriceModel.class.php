<?php
	class HomemakingServicePriceModel extends RelationModel{
		protected $connection = 'DB_CONFIG5';
        //protected $trueTableName = 'homemaking_sitter';
		//protected $dbName = 'mak_businessT.dbo';
		
		protected $fields = array(
        	'fldId','fldName','fldRemark','fldType','fldCreateDate','fldOwner','lastEditdt','lastEditby','_pk' => 'fldId','_autoinc' => true
    	);
		
		/*ThinkPHP可以很轻松的完成数据表的关联CURD操作，目前支持的关联关系包括下面四种：
		HAS_ONE、BELONGS_TO、HAS_MANY和MANY_TO_MANY。*/
		protected $_link = array(
		   	'homemaking_service_price_detail'  =>  array(
		        'mapping_type'    	=>HAS_MANY,
                'class_name'    	=>'homemaking_service_price_detail',
	          	//'mapping_name'		=>'HomeInfo',
	            'foreign_key'		=>'fldParentId'
		    )
		);
	}