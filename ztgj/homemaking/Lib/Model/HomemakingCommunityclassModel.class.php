<?php
	class HomemakingCommunityclassModel extends RelationModel{
		//创建育儿资源的关联模型类
		protected $connection = 'DB_CONFIG3';
        protected $trueTableName = 'homemaking_communityclass';
		//protected $dbName = 'mak_businessT.dbo';
		/*protected $fields = array(
        	'fldID','fldName','fldage','fldNative','fldEducation','fldLanguage','fldType','fldCreateDate','lastEditdt','lastEditby','_pk' => 'fldID','_autoinc' => true
    	);*/

		protected $_link = array(
		    'homemaking_communityitem'  =>  array(
		        'mapping_type'    	=>HAS_MANY,
                'class_name'    	=>'homemaking_communityitem',
	          	//'mapping_name'		=>'homemaking_communityitem',
	            'foreign_key'		=>'fldCommunityClass'//关联的外键名称
	            /*'relation_foreign_key'=>'fldCertificateId',//中间表的字段
	            'relation_table'=>'homemaking_communityclass_communityitem'*/	
		    )
		); 	
	}	