<?php
namespace Admin\Controller;

use Think\Controller;

class OrderController extends CommonController
{
    // 首页
    public function index()
    {
        $Order = D('Order');
        $count = $Order->count();
        $Page = new \Think\Page($count, 20);
        $show = $Page->show();
        $data = M('Order')->where(array(
            'status' => 0
        ))
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('id desc')
            ->select(); // 查询数据
        $this->assign('paging', $show);
        $this->assign('data', $data);
        $this->display();
    }

    public function search()
    {
        $keyword = I('post.keyword');
        $Order = D('Order');
        $count = $Order->count();
        $Page = new \Think\Page($count, 20);
        $show = $Page->show();
        $map['ord_num'] = array(
            'like',
            '%' . $keyword . '%'
        );
        $data = M('Order')->where($map)
            ->where(array(
            'status' => 0
        ))
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('id desc')
            ->select(); // 查询数据
        $this->assign('paging', $show);
        $this->assign('data', $data);
        $this->display('index');
    }

    public function search2()
    {
        $keyword = I('post.keyword');
        $Order = D('Order');
        $count = $Order->count();
        $Page = new \Think\Page($count, 20);
        $show = $Page->show();
        $map['ord_num'] = array(
            'like',
            '%' . $keyword . '%'
        );
        $data = M('Order')->where($map)
            ->where(array(
            'status' => 1
        ))
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('id desc')
            ->select(); // 查询数据
        $this->assign('paging', $show);
        $this->assign('data', $data);
        $this->display('old');
    }

    public function mod()
    {
        $Order = D('Order');
        if (IS_POST) {
            if (! $Order->create()) {
                $this->error($Order->getError());
            } else {
                $Order->status = 1;
                if ($Order->save()) {
                    $this->success('操作成功', U('Order/index'));
                } else {
                    $this->error('操作失败');
                }
            }
        }
        $id = I('get.id');
        $cdata = $Order->find($id);
        $this->assign('data', $cdata);
        $this->display();
    }

    public function mod2()
    {
        $Order = D('Order');
        if (IS_POST) {
            if (! $Order->create()) {
                $this->error($Order->getError());
            } else {
                if ($Order->save()) {
                    $this->success('操作成功', U('Order/old'));
                } else {
                    $this->error('操作失败');
                }
            }
        }
        $id = I('get.id');
        $cdata = $Order->find($id);
        $this->assign('data', $cdata);
        $this->display();
    }

    public function old()
    {
        $Order = D('Order');
        $count = $Order->count();
        $Page = new \Think\Page($count, 20);
        $show = $Page->show();
        $data = M('Order')->where(array(
            'status' => 1
        ))
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('id desc')
            ->select(); // 查询数据
        $this->assign('paging', $show);
        $this->assign('data', $data);
        $this->display();
    }
    // 退单
    public function back()
    {
        $id = I("get.id");
        $score = r_od_score($id);
        $Order = D('Order');
        $oinfo = $Order->find($id);
        $Order->id = $id;
        if ($Order->delete()) {
            mb_score($oinfo['mbid'], $score);
            $this->success('退单成功');
        } else {
            $this->error('退单失败');
        }
    }
    // 删除
    public function del()
    {
        $id = I("get.id");
        $Order = D('Order');
        $Order->id = $id;
        if ($Order->delete()) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    public function export()
    {
        $status = I('get.status');
        if ($status) {
            $map['status'] = 1;
            $ststusname = "已发货订单";
        } else {
            $map['status'] = 0;
            $ststusname = "最新订单";
        }
        $data = M('Order')->where($map)
            ->field('mbid,ptid,num,ord_num,kuaidi,kuaidi_num,order_date')
            ->order('id desc')
            ->select(); // 查询数据
        $filename = $ststusname . "_";
        $headArr = array(
            "姓名",
            "商品名称",
            "订购数量",
            "订单号",
            "物流公司",
            "运单号",
            "订购时间"
        );
        $array = array();
        foreach ($data as $key => $value) {
            $array[$key] = $value;
            $array[$key]['mbid'] = r_mb_name($value['name']);
            $array[$key]['ptid'] = r_pt_title($value['ptid']);
            $array[$key]['ord_num'] = $value['ord_num'] . " ";
            $array[$key]['kuaidi_num'] = $value['kuaidi_num'] . " ";
            $array[$key]['order_date'] = date("Y-m-d h:i:sa", $value['order_date']);
        }
        export_xls($filename, $headArr, $array);
    }
}