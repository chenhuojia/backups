<?php
/**
 * Created by 七月
 * User: 七月
 * Date: 2017/2/18
 * Time: 12:35
 */
namespace api\common\validate;
use api\common\validate\Base;
class TokenGet extends Base
{
    protected $rule = [
        'token' => 'require|isNotEmpty'
    ];
    
    protected $message=[
        'token' => 'Token不能为空'
    ];
    
    protected function isNotEmpty($value){
        return (empty($value))?false:true;
    }
}
