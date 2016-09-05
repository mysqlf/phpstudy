<?php
namespace Home\Controller;
use Think\Controller;
//公共继承类
class CommonController extends Controller {
    public function _initialize () {
        //移动设备浏览，则切换模板
        if (ismobile() || $_SESSION['IS_MOBILE']) {
            //设置默认默认主题为 Mobile
            C('DEFAULT_THEME','app');
            C('TMPL_PARSE_STRING',array('__IMG__' => __ROOT__.'/images/app'));
            define('IS_APP',1);
        }
        //验证登陆,没有登陆则跳转到登陆页面
        if(isset($_SESSION['mname']) || $_SESSION['mname'] ||isset($_SESSION['mid']) || $_SESSION['mid']){
            define('MID',$_SESSION['mid']);
            $mbinfo=M('Member')->find(MID);
            $this->assign('mbinfo',$mbinfo);
            // 购物车信息
            $Cart = new \Vendor\Cart();
            $cartnum=$Cart->getCnt();
            $this->assign('cartnum',$cartnum);
        }
        if(CONTROLLER_NAME=="Member" || CONTROLLER_NAME=="Recharge" || CONTROLLER_NAME=="Cart"){
            //验证登陆,没有登陆则跳转到登陆页面
            if(!isset($_SESSION['mname']) || $_SESSION['mname']==''||!isset($_SESSION['mid']) || $_SESSION['mid']==''){
                //兼容手机端
                if(ismobile() || $_SESSION['IS_MOBILE']){
                    $this->redirect("Index/login");
                }else{
                    $this->error("请先登录！",'',2);
                }
                exit;
            }
        }
        if(CONTROLLER_NAME=="Recharge"){
            vendor('Alipay.Corefunction');
            vendor('Alipay.Md5function');
            vendor('Alipay.Notify');
            vendor('Alipay.Submit');
        }
        $this->assign('sys',M('System')->find());//分配系统信息
        //最新订单
        $new_order=M('Order')->order('order_date desc')->limit(10)->select();
        $this->assign('new_order',$new_order);
        //全局变量
        $blockdata=M('Block')->select();
        if ($blockdata) {
            $block=array();
            foreach ($blockdata as $key => $value) {
                $block[$value['id']]=$value['content'];
                $block[$value['mark']]=$value['content'];
            }
            $this->assign('block',$block);
        }
        //全局广告
        $addata=M('Doc_ad')->select();
        if ($addata) {
            $ad=array();
            foreach ($addata as $key => $value) {
                $ad[$value['id']]=array(
                    'title'=>$value['title'],
                    'type'=>$value['type'],
                    'code'=>$value['code'],
                    'img'=>$value['img'],
                    'link'=>$value['link'],
                    'onoff'=>$value['onoff'],
                );
            }
            $this->assign('ad',$ad);
        }
    }
}