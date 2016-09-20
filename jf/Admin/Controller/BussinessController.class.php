<?php
namespace Admin\Controller;
use Think\Controller;
class BussinessController  extends CommonController {
    //首页
 public function index(){
        $cid=I('get.cid');
        $mid=I('get.mid');
        //模型数据
        $model_data=M('Model')->find($mid);
  
        $this->assign('mdata',$model_data);
        

        if ($cid) {
            $map['cid']=$cid;
            $this->assign('cid',$cid);
        }
        //获取该模型下所有文档
        $tablename="Doc_".$model_data['mark'];
        $Doc=M($tablename);
        $count= $Doc->where($map)->count();
        $Page = new \Think\Page($count,12);
        $show = $Page->show();

        $catelist=M("Category")->where(array('mid'=>$mid))->select();
        $this->assign('catelist',$catelist);

        $data=$Doc->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
        $this->assign('paging',$show);
        $this->assign('data',$data);
        if ($model_data['list_type']) {
            $this->display($model_data['list_type']);
        }else{
            $this->display();
        }
    }
        
        
        public function search()
        {
            $keyword = I('post.keyword');
            $Business = D('Business');
            $count = $Business->count();
            $Page = new \Think\Page($count, 20);
            $show = $Page->show();
         /*    $map['ord_num'] = array(
                'like',
                '%' . $keyword . '%'
            ); */
            $data = M('Business')->where($map)
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
    
    
}

?>