<?php
namespace Admin\Controller;
use Think\Controller;
class SystemController extends CommonController {
    // 网站设置
    public function setting(){
        $System=D('System');
        if (IS_POST) {
            if (!$System->create()){     
                $this->error($System->getError());
            }else{
                if ($System->save()) {
                    $this->success('设置成功',U('System/setting'));
                }else{
                    $this->error('没有设置任何项');
                }
            }
            return;
        }
        $data=$System->find(1);
        $this->assign('data',$data);
        $this->display();
    }
}