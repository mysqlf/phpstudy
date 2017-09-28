<?php
use Illuminate\Database\Capsule\Manager as DB;

class ApiController extends AbstractController {
    public function api_listAction(){
        $request=$this->getRequest();
        $cate=$request->get('cate');
        if ($cate!==null) {
            $data['cate']=$cate;
            $data['list']=ApiModel::get_api_list_of_cate($cate);
            $this->_view('api/api_list.php',$data);
        }
    }
    /**
     * [api_infoAction 接口详情页]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-09-22
     * @return   [type]     [description]
     */
    public function api_infoAction(){
        $request=$this->getRequest();
        $apiid=$request->get('apiid');
        if ($apiid!==null) {
            $data['list']=ApiModel::get_api_info($apiid);
            $this->_view('api/api_info.php',$data);
        }
    }
    /**
     * [api_editAction 接口编辑]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-09-22
     * @return   [type]     [description]
     */
    public function api_editAction(){
        $request=$this->getRequest();
        $apiid=$request->get('apiid');
        if ($apiid!==null) {
            $data['info']=ApiModel::find($apiid)->toArray();
            $parameter=unserialize($data['info']['parameter']);
            $data['info']['parameter']=$parameter;
            $this->_view('api/api_edit.php',$data);
        }else{
            $data['num'] = htmlspecialchars($_POST['num'],ENT_QUOTES);
            $data=$request->getPost();
            $id=$data['id'];//id
            unset($data['id']);
            $data['parameter'] = serialize($data['p']); //序列化参数
            unset($data['p']);
            $res=ApiModel::update_api($id,$data);
            if ($res) {
                success('修改成功');
            }else{
                error('修改失败');
            }

        }
    }
    /**
     * [api_addAction 添加api]
     * @author Greedywolf 1154505909@qq.com
     * @DateTime 2017-07-13
     * @return   [type]     [description]
     */
    public function api_addAction(){
        $request=$this->getRequest();
        $data=$request->getPost();
        if (empty($data)) {
            $data['cate']=$request->get('cate');
            $this->_view('api/api_add.php',$data);
        }else{
            //接口编号
            $data['num'] = htmlspecialchars($_POST['num'],ENT_QUOTES);   
            $data['parameter'] = serialize($data['p']); //序列化参数
            unset($data['p']);
            $data['lasttime'] = time();                 //最后操作时间
            $data['isdel'] = 0;                         //是否删除的标识
            $res=ApiModel::insert_api($data);
            if ($res) {
                success('成功');
            }else{
                error('失败');
            }
        }
    }
    
}
