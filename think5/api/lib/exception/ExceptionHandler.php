<?php
namespace api\lib\exception;
use think\exception\Handle;
use Exception;
use think\Request;
use think\Log;
class ExceptionHandler extends Handle{
    
    private $code;
    private $msg;
    private $errorCode;
    private $requestUrl;
    private $requestTime;
    
    public function render(\Exception $e){
        if ($e instanceof BaseException){
            //自定义异常
            $this->code         =   $e->code;
            $this->msg          =   $e->msg;
            $this->errorCode    =   $e->errorCode;
        }else{
            if (config('app_debug')){
                return parent::render($e);
            }
            $this->code         =   500;
            $this->msg          =   '服务器内部错误';
            $this->errorCode    =   99999;
            $this->errorLog($e);
        }
        $request= Request::instance();
        $result=[
            'errorCode'     =>  $this->errorCode,
            'msg'           =>  $this->msg,
            'requestTime'   =>  date('Y-d-m H:i:s',$request->time()),
            'requestUrl'    =>  $request->domain().$request->url(),
        ];
        return json($result,$this->code);
    }
    
    private function errorLog(\Exception $e){
        Log::init([
            'type'  =>  'File',
            'path'  =>  LOG_PATH,
            'apart_level'   =>  ['error','sql'],
            'level' => ['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}