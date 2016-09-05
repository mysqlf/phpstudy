<?php
namespace Admin\Controller;
use Think\Controller;
class LogController extends CommonController {
    public function score_log(){
        $member_id=I('id');
        if($member_id){
            $map['member_id']=$member_id;
        }
        $keyword=I('post.keyword');
        if($keyword){
            $map['member_name']=array('like','%'.$keyword.'%');
            $map['member_username']=array('like','%'.$keyword.'%');
            $map['_logic'] = 'OR';
        }
        $count= M('Score_log')->where($map)->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $data=M('Score_log')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->display();
    }
    public function score_make(){
        if (IS_POST) {
            $mbid=I('post.mbid');
            $score=I('post.score');
            $mark=I('post.mark');
            $note=I('post.note');
            if (empty($score)) {
                $this->error('积分不能为空');
            }
            if (empty($mbid)) {
                $this->error('会员不存在');
            }
            if ($mark !=1 && $mark !=-1) {
                $this->error('操作状态错误');
            }
            set_member_score_log($mbid,$score,$mark,$note);
            $re=set_member_score($mbid,$score,$mark);
            if ($re) {
                $this->success('操作成功',U('Log/score_log',array('id'=>$mbid)));
            }else{
                $this->error('操作失败');
            }
        }
        $mbid=I('get.id');
        $Member=M('Member');
        $mbinfo=$Member->where(array('id'=>$mbid))->find();
        if (empty($mbinfo)) {
            $this->error('用户不存在');
        }
        $this->assign('mbinfo',$mbinfo);
        $this->display();
    }
    public function score_del(){
        $id=I("get.id");
        $Trade=D('Score_log');
        $Trade->id=$id;
        if ($Trade->delete()) {
            $this->success('删除成功',U('Log/score_log'));
        }else{
            $this->error('删除失败');
        }
    }
    public function score_search(){
        $keyword=I('post.keyword');
        $map['member_name']=array('like','%'.$keyword.'%');
        $map['member_username']=array('like','%'.$keyword.'%');
        $map['_logic'] = 'OR';
        $count= M('Score_log')->where($map)->count();
        $Page = new \Think\Page($count,15);
        $show = $Page->show();
        $data=M('Score_log')->where($map)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();//查询数据
        $this->assign('paging',$show);
        $this->assign('data',$data);
        $this->display('index');
    }
    public function score_export(){
        //此处区分管理员下属的会员
        $markname="积分日志";
        $mark=I('get.mark');
        if($mark==1){
            $map['mark']=1;
            $markname="增加积分";
        }
        if($mark==2){
            $map['mark']=2;
            $markname="减少积分";
        }
        $data=M('Score_log')->where($map)->order('id desc')->select();//查询数据
        $filename="积分日志_";
        $headArr=array("ID","时间",$markname,"备注","积分值","会员ID");
        export_xls($filename,$headArr,$data);
    }
}