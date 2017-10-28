<?php
namespace   api\extend;
use think\Config;
class Alipay {
    
    public function pay(){
        Config::load(APP_PATH.'extra/alipay.php');
        dump(config());
    }
    
    
}