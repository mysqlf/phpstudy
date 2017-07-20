<?php
use Illuminate\Database\Capsule\Manager as DB;
class IndexController extends AbstractController {
	public function indexAction() {
        $request=$this->getRequest();
        $data=$request->getPost();
        if (empty($data)) {
            $this->_view('api/api_add.php');
        }else{
            $users = UserModel::getone(1)->toArray();
            print_r($users);
        }
		
        #$res=ApiModel::insert_data(array('api_name'=>'test','api_url'=>'www.yaf.com','api_param'=>'mmp','api_table'=>'car','update_time'=>date('Y-m-d H:i:s')));
        #print_r($res);
		//$user = DB::table('infos')->first();
        //print_r($user);die();
		//$this->getView()->assign("content", "Hello Yaf");
	}
}
