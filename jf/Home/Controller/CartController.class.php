<?php
namespace Home\Controller;
use Think\Controller;
class CartController extends CommonController {
    public function index(){
        $Care = new \Vendor\Cart();
        $this->assign('data',$_SESSION['cart']);
        $allprice=$Care->getPrice();
        $this->assign('allprice',$allprice);
        $this->assign('active',4);
        $this->display();
    }
    public function add(){
        $type=I('post.type');
        $id=I('post.id');
        $num=I('post.num');
        if (empty($id) || empty($num)) {
            $this->error("参数错误");
        }
        //产品信息
        $goods_info=M('Doc_product')->where(array('id'=>$id))->find();
        //检测库存
        if($num > $goods_info['gnum']){
            $this->error("该商品库存不足");
        }
        $member_indo=M('Member')->where(array('id'=>MID))->find();
        if(empty($member_indo)){$this->error("会员不存在");}
        if($goods_info['level'] > $member_indo['level']){
            $this->error("您没有权限兑换此商品");
        }
        if ($goods_info) {
            $Care = new \Vendor\Cart();
            $Care->addItem($id,$goods_info['title'],$goods_info['score'],$num,$goods_info['thumb']);
            if($type==1){
                $this->success("结算中..",U('Cart/index'));
            }else{
                $this->success("成功加入购物车");
            }
        }else{
            $this->error("该商品不存在");
        }
    }
    public function del(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error("参数错误");
        }
        $Care = new \Vendor\Cart();
        $Care->delItem($id);
        $this->success("删除成功");

    }
    public function num(){
        $id=I('post.id');
        $type=I('post.type');
        if (empty($id)) {
            $this->error("参数错误");
        }
        //产品信息
        $goods_info=M('Doc_product')->where(array('id'=>$id))->find();
        $Cart = new \Vendor\Cart();
        $num=$_SESSION['cart'][$id]['num'];
        if ($type==1) {
            $num=$num+1;
            //检测库存
            if($num > $goods_info['gnum']){
                $this->error("该商品库存不足");
            }
            $Cart->incNum($id);
        }
        if ($type==2) {
            $num=$num-1;
            //检测库存
            if($num > $goods_info['gnum']){
                $this->error("该商品库存不足");
            }
            $Cart->decNum($id);
        }
        $this->success("操作成功");

    }
    // 计算
    public function buy(){
        $buydata=$_SESSION['cart'];
        if (empty($buydata)) {
            $this->error("请在购物车里面添加商品");
        }
        $Care = new \Vendor\Cart();
        $member_info=M('Member')->where(array('id'=>MID))->find();
        if(empty($member_info)){$this->error("该会员不存在");}
        $totle_score=$Care->getPrice();
        if ($member_info >= $totle_score) {
            $re1=set_member_score(MID,$totle_score,-1);//扣掉积分
            if ($re1) {
                $Order=M('Order');
                foreach ($buydata as $key => $value) {
                    $Order->mbid = MID;
                    $Order->mbname = r_mb_name(MID);
                    $Order->ptid = $value['id'];
                    $Order->ptname = r_p_tit($value['id']);
                    $Order->num = $value['num'];
                    $Order->score = $value['num']*$value['price'];
                    $Order->order_date = time();
                    $Order->ord_num = build_order_no();
                    $re=$Order->add();
                    if (!$re) {
                        $this->error("订单生成错误");
                    }
                    set_member_score_log(MID, $value['score'], -1, "积分兑换商品：" . r_p_tit($value['id']));
                }
                $Care->clear();
                $this->success("兑换成功",U('Member/neworder'));
            }else{
                $this->error("系统扣除积分错误");
            }
        }else{
            $this->error("您的积分不足购买此商品");
        }
    }
}