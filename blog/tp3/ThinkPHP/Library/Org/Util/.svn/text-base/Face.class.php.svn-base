<?php
namespace Org\Util;
class Face{
    private $img=null;
    private static $host = "https://dm-23.data.aliyun.com";
    private static $path = "/rest/160601/face/age_detection.json";
    public function __construct(){
        
    }
    
    public static function get_age($img){
        if (is_file($img)){
            $base64_img = self::base64EncodeImage($img);
        }else{
            $base64_img=$base64_image = chunk_split(base64_encode($img));
        } 
        $data=self::curl(self::request_data($base64_img));
        $data=json_decode($data,true);
        return $data;
    }
    
   public static function curl($bodys){        
        $method = "POST";
        $appcode = "83efbe43c26840ebbaff9ae57ebde1f3";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/json; charset=UTF-8");
        $querys = "";
        $url = self::$host . self::$path;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".self::$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data=curl_exec($curl);
        $body=array();
        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == '200') {
            $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($data, 0, $headerSize);
            $body = substr($data, $headerSize);
        }
        $body=json_decode($body);
        return $body->outputs[0]->outputValue->dataValue;
    }
    
   public static function base64EncodeImage ($image_file) {
      $base64_image = '';
      $image_info = getimagesize($image_file);
      $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
      $base64_image = chunk_split(base64_encode($image_data));
      return $base64_image;
   }
   
   private static function request_data($base64_img){
       $data=array(
           'inputs'=>array(
               array(
                   'image'=>array(
                       'dataType'=>50,
                       'dataValue'=>$base64_img,
                   ),
                   'type'=>array(
                       'dataType'=>10,
                       'dataValue'=>2,
                   ),
               )
           ),
       );
       return json_encode($data);
   }
}


