<?php
	//公共函数
    function authcheck($rule,$uid){
        //判断当前用户UID是否在定义的超级管理员参数里
        if(in_array($uid,C('ADMINISTRATOR'))){
            //如果是超级管理员ID,或者是后台首页不需要进行权限验证
            return true;
        }else{
            //后台首页不设置权限
            if (in_array($rule,array('Index/index'))) {
                return true;
            }
            $auth=new \Think\Auth();
            if(!$auth->check($rule,$uid)){
                return false;
            }else{
                return true;
            }
        }
    }
    function cutstr($string,$length=0,$dot='',$html=false)
    {
        if(!$length || !trim($string)){
            return $string;
        }
        $str = $string;
        $string = strip_tags(trim($string));
        $string = str_replace("&nbsp;"," ",$string);
        if(strlen($string) <= $length){
            return $html ? $str : $string;
        }
        $info = _substr($string,$length,$dot);
        if(!$html){
            return $info;
        }
        //组成HTML样式
        $starts = $ends = $starts_str = false;
        preg_match_all('/<\w+[^>]*>/isU',$str,$starts,PREG_OFFSET_CAPTURE);
        preg_match_all('/<\/\w+>/isU',$str,$ends,PREG_OFFSET_CAPTURE);
        if(!$starts || ($starts && !$starts[0])){
            return str_replace(" ","&nbsp;",$info);
        }
        $lst = $use = false;
        foreach($starts[0] as $key=>$value){
            if($value[1] >= $length){
                break;
            }
            $info = substr($info,0,$value[1]).$value[0].substr($info,$value[1]);
            $length += strlen($value[0]);
            if($ends && $ends[0][$key]){
                $chk = str_replace(array('/','>'),'',$ends[0][$key][0]);
                if(substr($value[0],0,strlen($chk)) == $chk){
                    $info = substr($info,0,$ends[0][$key][1]).$ends[0][$key][0].substr($info,$ends[0][$key][1]);
                    $length += strlen($ends[0][$key][0]);
                    $use[$key] = $ends[0][$key];
                }else{
                    $lst[] = $value[0];
                }
            }else{
                $lst[] = $value[0];
            }
        }
        if($ends && $lst){
            foreach($ends[0] as $key=>$value){
                if($use && $use[$key]){
                    continue;
                }
                $chk = str_replace(array('/','>'),'',$value[0]);
                foreach($lst as $k=>$v){
                    if(substr($v,0,strlen($chk)) == $chk){
                        $info = substr($info,0,$value[1]).$value[0].substr($info,$value[1]);
                        $length += strlen($value[0]);
                        $use[$key] = $value;
                        unset($lst[$k]);
                    }
                }
            }
        }
        return $info;
    }
    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    function getfiles($path, $allowFiles, &$files = array()){
        if (!is_dir($path)) return null;
        if(substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    getfiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(".$allowFiles.")$/i", $file)) {
                        $files[] = array(
                            'url'=> substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime'=> filemtime($path2)
                        );
                    }
                }
            }
        }
        return $files;
    }
    //返回模型名称
    function r_model_name($id){
        if ($id==-1) {
            return "单页";
        }
        if ($id) {
            $data=M('Model')->find($id);
            $r=$data ? $data['title'] : false;
            return $r;
        }else{
            return "";
        }
        
    }
    //返回分类名称
    function r_category_name($id){
        if ($id) {
            $data=M('Category')->find($id);
            $r=$data ? $data['name'] : false;
            return $r;
        }else{
            return "";
        }
        
    }
    //下拉选择框控件
    function form_select($name,$default=0,$att,$title="",$is_must=0){
        if ($name && $att) {
            $arr1=explode(",",$att);
            $arr2=array();
            if ($arr1) {
                foreach ($arr1 as $key => $value) {
                    $arr2[]=explode(":",$value);
                }
                foreach ($arr2 as $key => $value) {
                    if ($default) {
                        if ($default==$value[1]) {
                            $str2.="<option selected value='".$value[1]."' > ".$value[0]."</option>";
                        }else{
                            $str2.="<option value='".$value[1]."' > ".$value[0]."</option>";
                        }
                    }else{
                        $str2.="<option value='".$value[1]."' > ".$value[0]."</option>";
                    }  
                }
                if ($is_must) {
                    $str1="<select datatype='*' nullmsg='".$title."必须选择' name='".$name."'><option value=''>请选择</option>";
                }else{
                    $str1="<select name='".$name."'><option selected value=''>请选择</option>";
                }
                $str3="</select>";
            }
            return $str1.$str2.$str3;
        }else{
            return "下拉框控件错误";
        }
    }
    //单选按钮控件
    function form_radio($name,$default=0,$att,$title="",$is_must=0){
        if ($name && $att) {
            $arr1=explode(",",$att);
            $arr2=array();
            if ($arr1) {
                foreach ($arr1 as $key => $value) {
                    $arr2[]=explode(":",$value);
                }
                foreach ($arr2 as $key => $value) {
                    if ($default || $default==0) {
                        if ($default==$value[1]) {
                            $str.=$value[0]." <input type='radio' checked='checked' name='".$name."' value='".$value[1]."'/>　";
                        }else{
                            $str.=$value[0]." <input type='radio' name='".$name."' value='".$value[1]."'/>　";
                        }
                    }else{
                        $str.=$value[0]." <input type='radio' name='".$name."' value='".$value[1]."'/>　";
                    }  
                }
            }
            return $str;
        }else{
            return "单选控件错误";
        }
    }
    function form_ueditor($name,$value="",$att=500){
        if ($name) {
            $str.="<script id='UE_".$name."' name='".$name."' type='text/plain' style='width:95%;height:".$att."px;'>".html($value)."</script>";
            if (JSUEDITOR!=1) {
                $str.="<script type='text/javascript' src='".__ROOT__."/Public/ueditor/ueditor.config.js'></script>";
                $str.="<script type='text/javascript' src='".__ROOT__."/Public/ueditor/ueditor.all.min.js'></script>";
                define("JSUEDITOR",1); 
            }
            if (JSUEDITOR_PLUG!=1) {
                $str.="<script type='text/javascript' src='".__ROOT__."/Public/ueditor/plug.js'></script>";
                define("JSUEDITOR_PLUG",1);
            }
            $str.="<script type='text/javascript'>var ue = UE.getEditor('UE_".$name."');</script>";
            return $str;
        }else{
            return "编辑器控件错误";
        }
    }
    function form_ueditor2($name,$value="",$att=500){
        if ($name) {
            $str.="<script id='UE_".$name."' name='".$name."' type='text/plain' style='width:95%;height:".$att."px;'>".html($value)."</script>";
            if (JSUEDITOR!=1) {
                $str.="<script type='text/javascript' src='".__ROOT__."/Public/ueditor/ueditor.config.js'></script>";
                $str.="<script type='text/javascript' src='".__ROOT__."/Public/ueditor/ueditor.all.min.js'></script>";
                define("JSUEDITOR",1); 
            }
            $str.="<script type='text/javascript'>var ue = UE.getEditor('UE_".$name."',{toolbars:[['Source', 'Undo', 'Redo','spechars','pasteplain','Bold','insertunorderedlist','insertorderedlist','unlink','link','horizontal','bold','italic','underline','fontborder','strikethrough','forecolor','backcolor','superscript','subscript','justifyleft','justifycenter','justifyright','justifyjustify','directionalityltr','directionalityrtl','indent','removeformat','autotypeset','lineheight','fontfamily','fontsize']],autoFloatEnabled:false});</script>";
            return $str;
        }else{
            return "编辑器控件错误";
        }
    }
    //获取单个图片地址
    function form_img($id){
        if ($id) {
            $data=M('Uploads')->find($id);
            $r=$data ? $data['name'] : "";
            return "<img src='".__ROOT__."/Uploads/th_".$r."'>";
        }else{
            return "";
        }
    }
    //获取多个图片地址
    function form_imgs($id,$name){
        if ($id) {
            $ids=substr($id, 0, -1);
            $data=M('Uploads')->where("id in (".$ids.")")->select();
            foreach ($data as $key => $value) {
                $str.="<div class='imgli' id=\"imgid_".$value['id']."\">";
                $str.="<img alt=\"".$value['id']."\" src=\"".__ROOT__."/Uploads/th_".$value['name']."\">";
                $str.="<span onclick=\"del_moreupimg('".$name."',".$value['id'].")\">删除</span>";
                $str.="</div>";
            }
            return $str;
        }else{
            return "";
        }
    }
    function form_upload($name,$att=0,$default=0){
        if ($name) {
            if ($default) {
                $src=form_img($default,$name);
                $str=<<<EOF
            <div id='upbox_$name' class='one_upload'>
                <div class='preview'>$src<input type='hidden' name='$name' value='$default'><span onclick="del_oneupimg('$name')">删除</span></div>
                <a class='btn btn-success radius' onclick='up_file_one("$name","$att")'>选择上传</a>
            </div>
EOF;
            }else{
                $str=<<<EOF
            <div id='upbox_$name' class='one_upload'>
                <div class='preview'></div>
                <a class='btn btn-success radius' onclick='up_file_one("$name","$att")'>选择上传</a>
            </div>
EOF;
            }
            return $str;
        }else{
            return "上传控件错误";
        }
    }
    function form_uploads($name,$att=0,$default=0){
        if ($name) {
            if ($default) {
                $src=form_imgs($default,$name);
                $str=<<<EOF
            <div id='upsbox_$name' class='more_upload'>
                    <div class='preview'>$src</div>
                    <input type='hidden' name='$name' value='$default'>
                    <a class='btn btn-success radius' onclick='up_file_more("$name","$att")'>选择上传</a>
            </div>
EOF;
            }else{
                $str=<<<EOF
            <div id='upsbox_$name' class='more_upload'>
                    <div class='preview'></div>
                    <input type='hidden' name='$name' value=''>
                    <a class='btn btn-success radius' onclick='up_file_more("$name","$att")'>选择上传</a>
            </div>
EOF;
            }
            return $str;
        }else{
            return "上传控件错误";
        }
    }
    function form_category($name,$att=0,$default=0){
        if ($name) {
            $clist=M('Category')->where(array('mid'=>$att))->order('sort asc')->select();
            $str.="<select datatype='*' nullmsg='分类必须选择' name='".$name."'><option selected value=''>请选择</option>";
            foreach ($clist as $key => $value) {
                $re=M('Category')->where(array('pid'=>$value['id']))->find();
                if (!$re) {
                    if ($default) {
                        if ($default==$value['id']) {
                            $str.="<option selected value=\"".$value['id']."\">".$value['name']."</option>";
                        }else{
                            $str.="<option value=\"".$value['id']."\">".$value['name']."</option>";
                        }
                    }else{
                        $str.="<option value=\"".$value['id']."\">".$value['name']."</option>";
                    }
                }   
            }
            $str.="</select>";
            return $str;
        }else{
            return "分类控件错误";
        }
    }
    function form_date($name,$title=0,$is_must=0,$default=0){
        if ($name) {
            if (JSDATE!=1) {
                $str.="<script type='text/javascript' src='".__ROOT__."/Public/laydate/laydate.js'></script>";
                define("JSDATE",1); 
            }
            if ($default) {
                $default=date("Y-m-d",$default);
                if ($is_must) {
                    $str.="<input readonly id='fdate_".$name."' datatype='*' nullmsg='".$title."不能为空' name='".$name."' class='laydate-icon input-text radius le2' value='".$default."'>";
                }else{
                    $str.="<input readonly id='fdate_".$name."' name='".$name."' class='laydate-icon input-text radius le2' value='".$default."'>";
                }
            }else{
                if ($is_must) {
                    $str.="<input readonly id='fdate_".$name."' datatype='*' nullmsg='".$title."不能为空' name='".$name."' class='laydate-icon input-text radius le2'>";
                }else{
                    $str.="<input readonly id='fdate_".$name."' name='".$name."' class='laydate-icon input-text radius le2'>";
                }
            }
            $str.="<script>laydate({elem:'#fdate_".$name."'});</script>";
            return $str;
        }else{
            return "分类控件错误";
        }
    }
    function r_mb_username($id){
        $mbinfo=M('Member')->find($id);
        return $mbinfo['username'] ? $mbinfo['username'] : "该用户不存在";
    }
    function r_mb_company($id){
        $mbinfo=M('Member')->find($id);
        return $mbinfo['company'];
    }
    function r_mb_address($id){
        $mbinfo=M('Member')->find($id);
        return $mbinfo['address'];
    }
    function r_pt_title($id){
        $mbinfo=M('Doc_product')->find($id);
        return $mbinfo['title'];
    }
    //返回订单积分和
    function r_od_score($id){
        $oinfo=M('Order')->find($id);
        if($oinfo){
            $pinfo=M("Doc_product")->find($oinfo['ptid']);
            $score=$pinfo['score']*$oinfo['num'];
            if($score){
                return $score;
            }else{
               return false;
            }
        }else{
            return false;
        }
    }
    //导入xls表格文件，传入文件路径，返回数组对象
    function import_xls($xlx_path){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        //要导入的xls文件，位于根目录下的Public文件夹
        $filename=$xlx_path;
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        import("Org.Util.PHPExcel.Reader.Excel5");
        //如果excel文件后缀名为.xlsx，导入这下类
        //import("Org.Util.PHPExcel.Reader.Excel2007");
        //$PHPReader=new \PHPExcel_Reader_Excel2007();
        $PHPReader=new \PHPExcel_Reader_Excel5();
        //载入文件
        $PHPExcel=$PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet=$PHPExcel->getSheet(0);
        //获取总列数
        $allColumn=$currentSheet->getHighestColumn();
        //获取总行数
        $allRow=$currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=2;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='A';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
            }

        }
        return $arr;
    }
    //导出表格
    //        $data=array(
    //            array('username'=>'zhangsan','password'=>"123456"),
    //            array('username'=>'lisi','password'=>"abcdefg"),
    //            array('username'=>'wangwu','password'=>"111111"),
    //        );
    //        $filename="member_excel";
    //        $headArr=array("用户名","密码");
    //        export_xls($filename,$headArr,$data);
    function export_xls($fileName,$headArr,$data){
        //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.Writer.Excel5");
        import("Org.Util.PHPExcel.IOFactory.php");
        //对数据进行检验
        if(empty($data) || !is_array($data)){
            die("data must be a array");
        }
        //检查文件名
        if(empty($fileName)){
            exit;
        }

        $date = date("Y_m_d",time());
        $fileName .= "_{$date}.xls";

        //创建PHPExcel对象，注意，不能少了\
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();

        //设置表头
        $key = ord("A");
        foreach($headArr as $v){
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
            $key += 1;
        }

        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();
        foreach($data as $key => $rows){ //行写入
            $span = ord("A");
            foreach($rows as $keyName=>$value){// 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j.$column, $value);
                $span++;
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        // $objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }