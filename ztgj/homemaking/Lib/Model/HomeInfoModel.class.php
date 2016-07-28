<?php
	class HomeInfoModel extends RelationModel{
		protected $connection = 'DB_CONFIG3';
        protected $trueTableName = 'HomeInfo';
		//protected $dbName = 'mak_businessT.dbo';
	/*	protected $fields = array(
        	'fldID','fldName','fldage','fldNative','fldEducation','fldLanguage','fldType','fldCreateDate','lastEditdt','lastEditby','_pk' => 'fldID','_autoinc' => true
    	);*/
		/*ThinkPHP���Ժ����ɵ�������ݱ�Ĺ���CURD������Ŀǰ֧�ֵĹ�����ϵ�����������֣�
		HAS_ONE��BELONGS_TO��HAS_MANY��MANY_TO_MANY��*/
		protected $_link = array(
		    /*'homemaking_community'  =>  array(
		        'mapping_type'    	=>HAS_MANY,
                //'class_name'    	=>'homemaking_community',
	          	//'mapping_name'		=>'homemaking_community',
	            'foreign_key'		=>'fldPersonId'
		    )
		    ,*/'homemaking_sitter'  =>  array(
		        'mapping_type'    	=>HAS_ONE,
                'class_name'    	=>'HomemakingSitter',
	          	//'mapping_name'		=>'homemaking_sitter',
	            'foreign_key'		=>'fldPersonId'
		    )
		    ,'homemaking_certificate'  =>  array(
		        'mapping_type'    	=>MANY_TO_MANY,
                'class_name'    	=>'HomemakingCertificate',
	          	//'mapping_name'		=>'homemaking_certificate',
	            'foreign_key'		=>'fldHomeId',//�м����ֶ�
	            'relation_foreign_key'=>'fldCertificateId',//�м����ֶ�
	            'relation_table'=>'homemaking_person_certificate'
		    )
		);
	}