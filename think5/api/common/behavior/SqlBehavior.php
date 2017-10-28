<?php
namespace api\common\behavior;
use think\Log;
class SqlBehavior {
    
    public function run()
    {   
        Log::init([
            'type'  =>  'File',
            'path'  =>  LOG_PATH,
            'apart_level'   =>  ['error','sql'],
            'level' => ['sql']
        ]);
        
    }
}