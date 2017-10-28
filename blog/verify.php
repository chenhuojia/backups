<?php
class Verify{
  
  /**
   * 获取验证码
   * @param number $width
   * @param number $height
   * @param number $length
   * @param number $pixel
   * @param number $line
   * @param number $arc
   * @param number $type
   * @param string ||array $fontFile
   * @return boolean
   * ***/
  public static function getVeify($width=200,$height=40,$length=4,$type=4,$fontFile='STSONG.TTF',$pixel=50,$line=2,$arc=2){                
        if (is_array($fontFile) || !empty($fontFile)){
            if (is_array($fontFile)){
                $fontFile=array_rand($fontFile);
            }
        }else{
            return false;
        } 
        session_start();
        switch ($type){
            case 1:
                $string=join('',array_rand(range(0,9),$length));
                break;
            case 2:
                $string=join('',array_rand(array_flip(array_merge(range('a','z'),range('A','Z'))),$length));               
                break;
            case 3:
                $string=join('',array_rand(array_flip(array_merge(range('1','9'),range('a','k'),range('m','n'),range('p','z'),range('A','N'),range('P','Z'))),$length));               
                
                break;
            case 4:
                $str='当,应,用,写,好,后,环,境,数,据,库,不,同,时,就,要,对,的,抽,象,层,重,项,目,有,相,关,代,码,所,以,能,否,把,访,问,抽,象,出,来,用,不,同,数,据,库,时,只,要,切,换,不,同,实,现,就,了';
                $arr=array_unique(explode(',',$str));
                $string=join('',array_rand(array_flip($arr),$length));
                break;
            default:return false;break;
        }
        $image=imagecreatetruecolor($width,$height);
        $white=imagecolorallocate($image,255,255,255);
        imagefilledrectangle($image,0,0,$width,$height,$white);           
        //$texts=null;        
        $_SESSION['text']=self::addText($image, $string, $length, $width, $height,$fontFile);
        self::addPixel($pixel,$image, $width, $height);       
        self::addLine($line, $image, $width, $height);
        self::addArc($arc, $image, $width, $height);
        header('content-type:image/png');
        imagepng($image);
        imagedestory($image);
    }

   /**
    * 生成随机颜色
    * @param unknown $image
    * ***/
    
   private static function getRandColor($image){
        return imagecolorallocate($image,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
    }
    
    /**
     * 生成随机字符
     * @param unknown $image
     * @param unknown $string
     * @param unknown $length
     * @param unknown $width
     * @param unknown $height
     * @param string $fontFile
     * @return string
     * ***/
    private static function addText($image,$string,$length,$width,$height,$fontFile){
        for($i=0;$i<$length;$i++){
            $randColor=self::getRandColor($image);
            $size=mt_rand(20,28);
            $angle=mt_rand(-15,15);
            $x=20+ceil($width/$length)*$i;
            $y=mt_rand(ceil($height/2),$height-10);
            $text=mb_substr($string,$i,1,'utf-8');
            $texts .=$text;
            imagettftext($image,$size,$angle,$x,$y,$randColor,$fontFile,$text);
        }
        return $texts;
    }

    /**
     * 添加线
     * @param unknown $line
     * @param unknown $image
     * @param unknown $width
     * @param unknown $height
     * ***/
    private static function addLine($line,$image,$width,$height){
        //添加线
        for($i=0;$i<$line;$i++){
            $randColor=self::getRandColor($image);
            imageline($image,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),$randColor);
        } 
    }
    
    /**
     * 添加点
     * @param unknown $pixel
     * @param unknown $image
     * @param unknown $width
     * @param unknown $height
     * ***/
    private static function addPixel($pixel,$image,$width,$height){
        //添加点
        for($i=0;$i<$pixel;$i++){
            $randColor=self::getRandColor($image);
            imagesetpixel($image,mt_rand(0,$width),mt_rand(0,$height),$randColor);
        }
    }
    
    /**
     * 添加圆弧
     * @param unknown $arc
     * @param unknown $image
     * @param unknown $width
     * @param unknown $height
     * ***/
    private static function addArc($arc,$image,$width,$height){
        //添加线
        for($i=0;$i<$arc;$i++){
            $randColor=self::getRandColor($image);
            imagearc($image,mt_rand(0,$width/2),mt_rand(0,$height/2),mt_rand(0,$width/2),mt_rand(0,$height/2),mt_rand(0,360),mt_rand(0,360),$randColor);
        }
    }
    
    /**
     * 添加虚线
     * @param unknown $dashedline
     * @param unknown $image
     * @param unknown $width
     * @param unknown $height
     * ***/
    private static function addDashedLine($dashedline,$image,$width,$height){
        for ($i=0;$i<$dashedline;$i++){
            $randColor=self::getRandColor($image);
            imagedashedline ($image ,mt_rand(0,$width),mt_rand(0,$height),mt_rand(0,$width),mt_rand(0,$height),$randColor);
        }
    }
}

Verify::getVeify();