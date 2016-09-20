<?php
/**
*
*/
class title
{
    private $value;
    public function gettitle(){
        return $this->title;
    }
    public function settitle($title){
        $this->title=$title;
    }
}

/*$tit=new title();
$tit->settitle('123');
print_r($tit->gettitle());*/
/*责任链模式*/
class level{
    public $level;
    public $top;
    public $line;
    public function setlevel($level){
        $this->level=$level;
    }
    public function uplevel($top){
        $top->setlevel($this->level);
       return $top->checklevel($this->level);
    }
    public function respon($name){
        return 'OK'.$name;
    }
    public function add($ok){
        $status=new status();
        $status->setstatus($ok);
    }
    public function getstatus(){
        $status=new status();
        return $status->getstatus();
    }
}
class one extends level{
    public $tops='two';
    public $self_level=1;
    public function checklevel(){
        if ($this->level==$this->self_level) {
            return $this->respon(__CLASS__);
        }else{
            $this->top=new $this->tops();
            $this->add(__CLASS__);
            return $this->uplevel($this->top);
        }
    }
}
class two extends level{
    public $tops='three';
    public $self_level=2;
    public function checklevel(){
        if ($this->level==$this->self_level) {
            return $this->respon(__CLASS__);
        }else{
            $this->top=new $this->tops();
            $this->add(__CLASS__);
            return $this->uplevel($this->top);
        }
    }
}
class three extends level{
    public $self_level=3;
    public function checklevel(){
        if ($this->level==$this->self_level) {
            return $this->respon(__CLASS__);
        }else{
            $this->top=new $this->tops();
            $this->add(__CLASS__);
            return $this->uplevel($this->top);
        }
    }
}
class status{
    public static $status;
    public function setstatus($sta){
        self::$status=self::$status.$sta;
    }
    public function getstatus(){
        return self::$status;
    }
}
$one=new one();
$one->setlevel(3);
print_r($one->checklevel());
echo "\n";
print_r($one->getstatus());
echo "\n";
phpinfo();
/*$one->setlevel(2);
print_r($one->checklevel());
echo "\n";
print_r($one->getstatus());
echo "\n";
$one->setlevel(1);
print_r($one->checklevel());
echo "\n";
print_r($one->getstatus());*/
 ?>
