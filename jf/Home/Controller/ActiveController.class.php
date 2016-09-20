<?php
namespace Home\Controller;
use Think\Controller;
//公共继承类
class ActiveController extends CommonController {
    //签到奖励积分
    public function signed(){
        if(IS_AJAX){
            $Member=M('Member');
            $sys_info=M('System')->find();
            $signed_info=$Member->field("score,signed,signed_time")->where(array('id'=>MID))->find();
            if(empty($signed_info)){
                $this->error("数据错误");
                return;
            }
            //当前时间
            $now_time=strtotime(date("Y/m/d"));
            //首次签到,初始化1
            if($signed_info['signed_time']==0){
                $data1 = array('signed_time'=>$now_time,'signed'=>1);
                $re1=$Member->where(array('id'=>MID))->setField($data1);
                if($re1){
                    $this->success("首次签到成功！");
                }else{
                    $this->error("签到失败");
                }
                return;
            }
            //判断连续天数不合格，初始化1
            $cha=$now_time - $signed_info['signed_time'];
            if($cha > 86400){
                $data2 = array('signed_time'=>$now_time,'signed'=>1);
                $re2=$Member->where(array('id'=>MID))->setField($data2);
                if($re2){
                    $this->success("签到成功！");
                }else{
                    $this->error("签到失败");
                }
                return;
            }
            if($cha == 0){
                $this->error("您今天已经签过到，明天继续加油！");
                return;
            }
            //达到条件
            $data3 = array('signed_time'=>$now_time,'signed'=>$signed_info['signed']+1);
            $re3=$Member->where(array('id'=>MID))->setField($data3);
            if($re3){
                $signed_info2=$Member->field("score,signed,signed_time")->where(array('id'=>MID))->find();
                if($signed_info2['signed'] == $sys_info['signed']){
                    $data4 = array('signed_time'=>$now_time,'signed'=>0,'score'=>$signed_info2['score']+$sys_info['signed_score']);
                    $re4=$Member->where(array('id'=>MID))->setField($data4);
                    if($re4){
                        $this->success("签到成功，您已连续签到".$sys_info['signed']."次，奖励".$sys_info['signed_score']."积分！");
                    }else{
                        $this->error("签到失败");
                    }
                }else{
                    $this->success("签到成功！");
                }
            }else{
                $this->error("签到失败");
            }
        }
        $this->display();
    }
}