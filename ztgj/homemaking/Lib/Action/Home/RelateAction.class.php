<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/14
 * Time: 16:46
 */

class RelateAction extends Action
{
    public function code()
    {
        import('ORG.Util.Image');
        Image::buildImageVerify($length=4, $mode=5, $type='png', $width=105, $height=46);
    }
}