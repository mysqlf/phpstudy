<?php
	class HomemakingServicePriceModel extends RelationModel{
		protected $connection = 'DB_CONFIG5';
        //protected $trueTableName = 'homemaking_sitter';
		//protected $dbName = 'mak_businessT.dbo';
		
		protected $fields = array(
        	'fldId','fldName','fldRemark','fldType','fldCreateDate','fldOwner','lastEditdt','lastEditby','_pk' => 'fldId','_autoinc' => true
    	);
		
		/*ThinkPHP���Ժ����ɵ�������ݱ�Ĺ���CURD������Ŀǰ֧�ֵĹ�����ϵ�����������֣�
		HAS_ONE��BELONGS_TO��HAS_MANY��MANY_TO_MANY��*/
		protected $_link = array(
		   	'homemaking_service_price_detail'  =>  array(
		        'mapping_type'    	=>HAS_MANY,
                'class_name'    	=>'homemaking_service_price_detail',
	          	//'mapping_name'		=>'HomeInfo',
	            'foreign_key'		=>'fldParentId'
		    )
		);
	}