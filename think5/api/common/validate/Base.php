<?php
namespace api\common\validate;
use think\Validate;
use think\Request;
use api\lib\exception\BaseException;
class Base extends Validate{
    
    public function goCheck(){
        $request = Request::instance();
        $params = $request->param();
        $result = $this->check($params);
        if($result){
            return true;
        }
        else{
            throw new BaseException(['msg'=>$this->error]);
        }
    }

     /**
     * @param array $arrays 通常传入request.post变量数组
     * @return array 按照规则key过滤后的变量数组
     * @throws ParameterException
     */
   /* public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    } 
     */
    
}