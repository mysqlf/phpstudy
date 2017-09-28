<?php
/**
 * 
 */
class RequestController extends AbstractController {

    public function indexAction() {//默认Action
        $request=$this->getRequest();
        $apiid=$request->get('apiid');
        if ($apiid!==null) {
            $info=ApiModel::find($apiid)->toArray();
            $param='';
            $url=$info['url'];
            switch ($info['req_type']) {
                case 'soap':
                    $res=self::soap($data,$url,$function);
                    break;
                case 'http':
                    $res=self::http();
                    break;
                case 'https':
                    $res=self::https();
                    break;
                default:
                    
                    break;
            }
        }
    }

    public function soap($param,$url,$function){
        $soap=SoapClient($url,array('encoding'=>'UTF-8'));
        $result=$client->$function($param);
    }
    public function http($url,$methodtype,$param=array()){

    

        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        if ($methodtype=='post') {
            //设置post方式提交
            curl_setopt($curl, CURLOPT_POST, 1);
            //设置参数
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        }
        
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);

        return $data;


    }
    public function https(){

    }
    public function create_table(){

    }
}
