<?php
	class HomemakingCommunityModel extends RelationModel{
		protected $connection = 'DB_CONFIG3';
        //protected $trueTableName = 'homemaking_sitter';
		//protected $dbName = 'mak_businessT.dbo';
		
		protected $fields = array(
        	'fldId','fldType','fldPersonId','fldClass','fldTitle','fldContent','fldViewNum','fldReplyNum','fldBindId','fldLocation','fldLikeNum','fldAuditor','fldAuditDate','fldAuditStatus','fldCreateDate','fldOwner','lastEditdt','lastEditby','_pk' => 'fldId','_autoinc' => true
    	);
		
		/*ThinkPHP���Ժ����ɵ�������ݱ�Ĺ���CURD������Ŀǰ֧�ֵĹ�����ϵ�����������֣�
		HAS_ONE��BELONGS_TO��HAS_MANY��MANY_TO_MANY��*/
		protected $_link = array(
		   	'homemaking_user_bind'  =>  array(
		        'mapping_type'    	=>BELONGS_TO,
                'class_name'    	=>'homemaking_user_bind',
	          	//'mapping_name'		=>'homemaking_community',
	            'foreign_key'		=>'fldBindId'
		    )
		);
	}