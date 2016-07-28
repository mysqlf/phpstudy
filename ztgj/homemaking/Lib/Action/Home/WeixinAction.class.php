<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/14
 * Time: 16:23
 */


define("TOKEN", "weixin");


class WeixinAction extends Action{

    public function index()
    {
        if (!isset($_GET['echostr'])) {
            $this->responseMsg();
        }else{
            $this->valid();
        }
    }

    private function valid()
    {
        $echoStr = I('get.echostr',0);
        $signature =I('get.signature',0);
        $timestamp =I('get.timestamp',0);
        $nonce = I('get.nonce',0);
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature){
            echo $echoStr;
            exit;
        }
    }

    private function responseMsg()
    {
        $postStr = file_get_contents("php://input");
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            //消息类型分离
            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }

    }

    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                //$content = "感谢关注".WECHAT_NAME."！值得雇主信赖的专业母婴护理机构！科学坐月子，健康一辈子！\n"."\n找月嫂：我要预约、服务介绍"."\n找育婴嫂：我要预约、服务介绍"."\n妈妈帮：客户中心、母婴问答、育儿资讯\n";/*.'<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri=http://ariaasia.gicp.net/Account-getopenid&response_type=code&scope=snsapi_base&state=123#wechat_redirect">注册账号</a>'.",\n或者使用智通人才网账号".'<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid='.APPID.'&redirect_uri=http://ariaasia.gicp.net/Account-login&response_type=code&scope=snsapi_base&state=123#wechat_redirect">绑定</a>'."。";*/
                $content = "感谢关注".WECHAT_NAME."！值得雇主信赖的专业母婴护理服务平台！智通月嫂、育婴师像妈妈一样呵护您的健康、照顾您的宝贝！让天下妈妈安心、放心、舒心！\n找月嫂、育婴嫂，或了解更多信息，请拨打专家咨询热线：0769-87073668。\n点击下方菜单栏“领红包”任性抢红包！\ue238";
                //转换emoji表情
            	$content = preg_replace("#\\\u([0-9a-f]+)#ie","iconv('UCS-2','UTF-8', pack('H4', '\\1'))",$content);
                $barcode_id = $object->EventKey;
                list($qrscene,$id) = explode("_", $barcode_id);

                if (!$id)
                {
                    $this->subscribeadd($qrscene,$object->FromUserName);
                }
                else
                {
                    $this->subscribeadd($id,$object->FromUserName);
                }
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "LOCATION":
                //$content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                $arr['lat'] = $object->Latitude;
                $arr['lng'] = $object->Longitude;
                $location = json_encode($arr);
                $name = $object->FromUserName;
                S($name,$location,86400);
                break;
                
            //已关注
            case 'SCAN':
                $content = "感谢关注".WECHAT_NAME."！值得雇主信赖的专业母婴护理服务平台！智通月嫂、育婴师像妈妈一样呵护您的健康、照顾您的宝贝！让天下妈妈安心、放心、舒心！\n找月嫂、育婴嫂，或了解更多信息，请拨打专家咨询热线：0769-87073668。";
                break;
            /*case "CLICK":
                $name = $object->FromUserName;
            	$content = $name.$object->EventKey;
            	
                break;*/
            default:
                //$content = "receive a new event: ".$object->Event;
                break;
        }
            $result = $this->transmitText($object, $content);
        return $result;
    }
    private function receiveLocation($object)
    {
        //$content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $arr['lat'] = $object->Location_X;
        $arr['lng'] = $object->Location_Y;
        $location = json_encode($arr);
        $name = $object->FromUserName;
        S($name,$location,86400);
        //$result = $this->transmitText($object, $content);
        return ;
    }

    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
    
    private function subscribeadd($barcode_id,$openid)
    {   
        $openid = (string)$openid;
        $data['barcode_id'] = $barcode_id;
        $m = M('ProjPtimeWechatCount','',DB_CONFIG2);

        $result = $m->where($data)->find();// $m->where($data)->setInc('cnt' , 1);

        //是否是推广二维码
        if($result['barcode_id']){
            $record = M('ProjPtimeWechatRecord','',DB_CONFIG2);
            $rec = clone($record);

            $where['openid'] = $openid;
            $where['active_type'] = 1;
            $res = $record->where($where)->find();

            if(is_null($res)){
                $rec->data(array('openid'=>$openid,'barcode_id'=>$barcode_id))->add();
                $m->cnt = $m->cnt+1;
                $m->save();
            }
        }
    }
}