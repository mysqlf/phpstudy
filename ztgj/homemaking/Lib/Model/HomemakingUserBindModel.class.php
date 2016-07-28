<?php
	class HomemakingUserBindModel extends RelationModel{
		protected $connection = 'DB_CONFIG3';
        //protected $trueTableName = 'homemaking_sitter';
		//protected $dbName = 'mak_businessT.dbo';
		
		protected $fields = array(
        	'fldId','fldOpenId','fldOpenName','fldOpenType','fldType','fldStatus','fldCreateDate','fldOwner','lastEditdt','lastEditby','_pk' => 'fldId','_autoinc' => true
    	);
		
		/*ThinkPHP���Ժ����ɵ�������ݱ�Ĺ���CURD������Ŀǰ֧�ֵĹ�����ϵ�����������֣�
		HAS_ONE��BELONGS_TO��HAS_MANY��MANY_TO_MANY��*/
		protected $_link = array(
		   	'HomemakingCommunity'  =>  array(
		        'mapping_type'    	=>HAS_MANY,
                'class_name'    	=>'HomemakingCommunity',
	          	//'mapping_name'		=>'homemaking_community',
	            'foreign_key'		=>'fldBindId'
		    )
		);
	}