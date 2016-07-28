<?php
	class HomemakingSitterModel extends RelationModel{
		protected $connection = 'DB_CONFIG5';
        //protected $trueTableName = 'homemaking_sitter';
		//protected $dbName = 'mak_businessT.dbo';
		
		protected $fields = array(
        	'fldId','fldPersonId','fldType','fldSelfComment','fldCreateDate','fldOwner','lastEditdt','lastEditby','_pk' => 'fldId','_autoinc' => true,'_fk' => 'fldPersonId'
    	);
		
		/*ThinkPHP���Ժ����ɵ�������ݱ�Ĺ���CURD������Ŀǰ֧�ֵĹ�����ϵ�����������֣�
		HAS_ONE��BELONGS_TO��HAS_MANY��MANY_TO_MANY��*/
		protected $_link = array(
		    /*'homemaking_certificate'  =>  array(
		        'mapping_type'    	=>MANY_TO_MANY,
                'class_name'    	=>'HomemakingCertificate',
	          	//'mapping_name'		=>'homemaking_certificate',
	            'foreign_key'		=>'fldSitterId',//�м����ֶ�
	            'relation_foreign_key'=>'fldCertificateId',//�м����ֶ�
	            'relation_table'=>'homemaking_sitter_certificate'
		    )*/
		   /*	,'HomeInfo'  =>  array(
		        'mapping_type'    	=>BELONGS_TO,
                'class_name'    	=>'HomeInfo',
	          	//'mapping_name'		=>'HomeInfo',
	            'foreign_key'		=>'fldPersonId'
		    )*/
//		    '����3'  =>  HAS_ONE, // ��ݶ���
		);
	}