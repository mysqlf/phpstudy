<?php
/**
* 
*/
class String
{
    public $str;
    public function checkStr($str,$rule){

    }
    public function setstr($str,$rule){
        if (self::checkStr($str,$rule)) {
            $this->str=$str;
        }else{
            return "value error";
        }
    }
    public function getstr(){
        return $this->str;
    }
}
class AccordingRuleCheck{
    public function 
}
 ?>}
