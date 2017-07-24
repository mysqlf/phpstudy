<?php
function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1){
        /* 下面两行只在线段直角相交时好使
        imagesetthickness($image, $thick);
        return imageline($image, $x1, $y1, $x2, $y2, $color);
        */
        if ($thick == 1) {
            return imageline($image, $x1, $y1, $x2, $y2, $color);
        }
        $t = $thick / 2 - 0.5;
        if ($x1 == $x2 || $y1 == $y2) {
            return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
        }
        $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
        $a = $t / sqrt(1 + pow($k, 2));
        $points = array(
            round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
            round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
            round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
            round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
        );
        imagefilledpolygon($image, $points, 4, $color);
        imagepolygon($image, $points, 4, $color);
    }
    // 创建画布
    $width = 200;   // 规定画布的宽高
    $height = 100;
    $image = imagecreate(400, 400);
    // 背景设为红色
    #$background = imagecolorallocate($im, 255, 0, 0);
    #$image = imagecreatetruecolor($width, $height);  // 创建一幅真彩色图像
    imagecolorallocate($image, 111, 111, 111);
    imagelinethick($image,10,10,20,10,255,0);
    imagelinethick($image,20,10,20,30,255,0);
    imagelinethick($image,20,30,30,30,255,0);
    imagelinethick($image,30,30,40,30,255,0);
    imagejpeg($image, 'simpletext.jpg');
    // 释放图像资源
    imagedestroy($image);
    
