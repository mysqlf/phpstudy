<?php
// +----------------------------------------------------------------------
// | 自定义标签库
// +----------------------------------------------------------------------
namespace Think\Template\TagLib;
use Think\Template\TagLib;
/**
 * Html标签库驱动
 */
class My extends TagLib{
    // 标签定义
    protected $tags   =  array(
        'scate' =>  array('attr'=>'pid,limit','level'=>3),
        'scate2' =>  array('attr'=>'pid,limit','level'=>3),
        'loop'=>array('attr'=>'table,where,order,limit,id,page,sql,field,key,mod,debug','level'=>3),
    );
    //万能调用，limit,调用条数，id 循环变量默认VO，key数组下表变量
    public function _loop($tag,$content){
         $table     =!empty($tag['table'])?$tag['table']:'Doc_article';
         $order     =!empty($tag['order'])?$tag['order']:'';
         $limit     =!empty($tag['limit'])?$tag['limit']:'';
         $id        =!empty($tag['id'])?$tag['id']:'vo';
         $where     =!empty($tag['where'])?$tag['where']:' 1 ';
         $key        =!empty($tag['key'])?$tag['key']:'i';
         $mod        =!empty($tag['mod'])?$tag['mod']:'2';
         $page      =!empty($tag['page'])?$tag['page']:false;
         $sql         =!empty($tag['sql'])?$tag['sql']:'';
         $field     =!empty($tag['field'])?$tag['field']:'';
         $debug     =!empty($tag['debug'])?$tag['debug']:false;
         $this->comparison['noteq']= '<>';
         $this->comparison['sqleq']= '=';
         $where     =$this->parseCondition($where);
         $sql         =$this->parseCondition($sql);
         $parsestr.='<?php $m=M("'.$table.'");';
         if($sql){
            if($page){
                $limit=$limit?$limit:10;//如果有page，没有输入limit则默认为10
                $parsestr.='$count=count($m->query("'.$sql.'"));';
                $parsestr.='$p = new \Think\Page( $count, '.$limit.' );';  //分页类引用
                $parsestr.='$sql.="'.$sql.'";';
                $parsestr.='$sql.=" limit ".$p->firstRow.",".$p->listRows."";';
                $parsestr.='$ret=$m->query($sql);';
                $parsestr.='$pages=$p->show();';
                //$parsestr.='dump($count);dump($sql);';  
            }else{
                $sql.=$limit?(' limit '.$limit):'';
                $parsestr.='$ret=$m->query("'.$sql.'");'; 
            }
         }else{
             if($page){
                 $limit=$limit?$limit:10;//如果有page，没有输入limit则默认为10
                 $parsestr.='$count=$m->where("'.$where.'")->count();';
                 $parsestr.='$p = new \Think\Page( $count, '.$limit.' );';
                 $parsestr.='$ret=$m->field("'.$field.'")->where("'.$where.'")->limit($p->firstRow.",".$p->listRows)->order("'.$order.'")->select();';
                 $parsestr.='$pages=$p->show();';
             }else{
                 $parsestr.='$ret=$m->field("'.$field.'")->where("'.$where.'")->order("'.$order.'")->limit("'.$limit.'")->select();'; 
             }            
         }      
         if($debug!=false){
            $parsestr.='dump($ret);dump($m->getLastSql());';
         }
         $parsestr.= 'if ($ret): $'.$key.'=0;';
         $parsestr.= 'foreach($ret as $key=>$'.$id.'):';
         $parsestr.= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
         $parsestr.= $this->tpl->parse($content);      
         $parsestr.= '<?php endforeach;endif;?>';   
         return $parsestr;
    }
    //传入分类ID，获取子分类
    public function _scate($tag,$content) {
        $pid  = isset($tag['pid'])?$tag['pid']:0;
        $limit      =   !empty($tag['limit'])?$tag['limit']:100;
        $id       =   !empty($tag['id'])?$tag['id']:"vo";
        $key        =   !empty($tag['key'])?$tag['key']:'i';
        if (is_numeric($pid)) {
            $datalist       =   "M('Category')->limit(".$limit.")->where('pid=".$pid."')->order('sort')->select()";
        }else{
            $pid  =   $this->autoBuildVar($pid);
            $datalist       =   "M('Category')->limit(".$limit.")->where('pid='.".$pid.")->order('sort')->select()";
        }
        $parseStr   =   '<?php if(is_array('.$datalist.')): foreach('.$datalist.' as $'.$key.'=>$'.$id.'): ?>';
        $parseStr  .=   $this->tpl->parse($content);
        $parseStr  .=   '<?php endforeach; endif; ?>';
        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }
    public function _scate2($tag,$content) {
        $pid  = isset($tag['pid'])?$tag['pid']:0;
        $limit      =   !empty($tag['limit'])?$tag['limit']:100;
        $id       =   !empty($tag['id'])?$tag['id']:"vo";
        $key        =   !empty($tag['key'])?$tag['key']:'i';
        if (is_numeric($pid)) {
            $datalist       =   "M('Doc_article')->limit(".$limit.")->where('cid=".$pid."')->select()";
        }else{
            $pid  =   $this->autoBuildVar($pid);
            $datalist       =   "M('Doc_article')->limit(".$limit.")->where('cid='.".$pid.")->select()";
        }
        $parseStr   =   '<?php if(is_array('.$datalist.')): foreach('.$datalist.' as $'.$key.'=>$'.$id.'): ?>';
        $parseStr  .=   $this->tpl->parse($content);
        $parseStr  .=   '<?php endforeach; endif; ?>';
        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }
}