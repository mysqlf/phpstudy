<?php
	class HomemakingCertificateModel extends RelationModel{
		protected $connection = 'DB_CONFIG5';
        //protected $trueTableName = 'homemaking_sitter';
		//protected $dbName = 'mak_businessT.dbo';
		
		protected $fields = array(
        	'fldId','fldName','fldStatus','fldType','fldCreateDate','fldOwner','lastEditdt','lastEditby','_pk' => 'fldId','_autoinc' => true
    	);
		
		/*ThinkPHP���Ժ����ɵ�������ݱ�Ĺ���CURD������Ŀǰ֧�ֵĹ�����ϵ�����������֣�
		HAS_ONE��BELONGS_TO��HAS_MANY��MANY_TO_MANY��*/
	/*	protected $_link = array(
		    'homemaking_sitter'  =>  array(
		        'mapping_type'    	=>MANY_TO_MANY,
                //'class_name'    	=>'homemaking_sitter',
	          	//'mapping_name'		=>'homemaking_sitter',
	            'foreign_key'		=>'fldCertificateId',//�м����ֶ�
	            'relation_foreign_key'=>'fldSitterId',//�м����ֶ�
	            'relation_table'=>'homemaking_sitter_certificate'
		    )
//		    '����2'  =>  array(
//		        '��������1' => '����',
//		        '��������N' => '����',
//		    ),
//		    '����3'  =>  HAS_ONE, // ��ݶ���
		);*/
	}