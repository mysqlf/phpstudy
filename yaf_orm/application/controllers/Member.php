<?php
use Illuminate\Database\Capsule\Manager as DB;
class MemberController extends AbstractController {
    public function indexAction() {//默认Action
        $data['user']=get_session('user');
        $this->_view('public/body.php',$data);
    }
     
}
