<?php
use Illuminate\Database\Capsule\Manager as DB;

class ApiController extends AbstractController {
    public function api_listAction(){
        $request=$this->getRequest();
        $cate=$request->get('cate');
        if ($cate!==null) {
            $data['list']=ApiModel::get_api_list_of_cate($cate);
            $this->_view('api/api_list',$data);
        }
    }
    public function api_infoAction(){
        $request=$this->getRequest();
        $apiid=$request->get('apiid');
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
            /*
            $data['name'] = $data['name'];              //接口名称
            $data['memo'] = $data['memo'];              //备注
            $data['des'] = $data['des'];                //描述
            $data['type'] = $data['type'];              //请求方式
            $data['url'] = $data['url'];                //链接
            $data['table']=$data['table'];              //数据表
            $data['re'] = $data['re'];                  //返回值
            */
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
