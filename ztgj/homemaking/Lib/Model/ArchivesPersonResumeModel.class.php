<?php
	class ArchivesPersonResumeModel extends Model{
        protected $trueTableName    =   'per_resume';
		/**
		 * 跟新数据（如果不存在则添加）
		 * @param  integer $id  [description]
		 * @param  array   $arr [description]
		 * @return [type]       [description]
		 */
		public function updateData($id = 0,$arr = array()){
			if($id){
				$map['account_id'] = $id;
                $map['resume_type'] =4;
				$this->where($map);
			}
			if($this->find()){
				$id = $this->id;
				foreach ($arr as $key => $value) {
					$this->$key = $value;
				}
				$res = $this->save();
				return $id;
			}else{
                $arr['resume_name'] = "我的微名片";
                $arr['resume_type'] = 4;
				$arr['account_id'] = $id;
				$this->add($arr);
				return $this->getLastInsID();
			}
		}
		
		/**
		 * 获取微名片单条记录
		 * @param  [type] $arr [description]
		 * @return [type]      [description]
		 */
		public function getResumeItem($arr){
			return $this->where($arr)->find();
		}
	}