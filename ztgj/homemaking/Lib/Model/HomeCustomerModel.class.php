<?php
	class HomeCustomerModel extends RelationModel{
		protected $connection = 'DB_CONFIG3';
        protected $trueTableName = 'HomeCustomer';
		//protected $dbName = 'mak_businessT.dbo';
		
		/*ThinkPHP���Ժ����ɵ�������ݱ�Ĺ���CURD������Ŀǰ֧�ֵĹ�����ϵ�����������֣�
		HAS_ONE��BELONGS_TO��HAS_MANY��MANY_TO_MANY��*/
		protected $_link = array(
		    'homemaking_community'  =>  array(
		        'mapping_type'    	=>HAS_MANY,
                //'class_name'    	=>'homemaking_community',
	          	//'mapping_name'		=>'homemaking_community',
	            'foreign_key'		=>'fldPersonId'
		    )
//		    '����2'  =>  array(
//		        '��������1' => '����',
//		        '��������N' => '����',
//		    ),
//		    '����3'  =>  HAS_ONE, // ��ݶ���
		);
	}