<?php
use Illuminate\Database\Capsule\Manager as DB;
class CateController extends AbstractController {
    public function cate_addAction() {
        $request=$this->getRequest();
        $data=$request->getPost();
        if (empty($data)) {
            $this->_view('cate/cate_add.php');
        }else{
            $res=CateModel::insert_cate($data);
            if ($res) {
                success('成功');
            }else{
                error('失败');
            }
        }
    }
    public function cate_editAction(){
        $request=$this->getRequest();
        $data=$request->getPost();
        if (empty($data)) {
            $aid=$request->get('cate');
            $cate['info']=CateModel::get_cate_info($aid);
            $this->_view('cate/cate_edit.php',$cate);
        }else{
            $aid=$data['aid'];
            unset($data['aid']);
            $res=CateModel::up_cate($aid,$data);
            if ($res) {
                success('成功');
            }else{
                error('失败');
            }
        }
    }
    public function cate_listAction() {     
        $data['list']=CateModel::get_cate_list();
        $this->_view('cate/cate_list.php',$data);
    }
}
