<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 鏁版嵁搴撶被鍨�
    'type'           => 'mysqli',
    // 鏈嶅姟鍣ㄥ湴鍧�
    'hostname'       => '127.0.0.1',
    // 鏁版嵁搴撳悕
    'database'       => 'blog',
    // 鐢ㄦ埛鍚�
    'username'       => 'root',
    // 瀵嗙爜
    'password'       => '123456',
    // 绔彛
    'hostport'       => '',
    // 杩炴帴dsn
    'dsn'            => '',
    // 鏁版嵁搴撹繛鎺ュ弬鏁�
    'params'         => [],
    // 鏁版嵁搴撶紪鐮侀粯璁ら噰鐢╱tf8
    'charset'        => 'utf8',
    // 鏁版嵁搴撹〃鍓嶇紑
    'prefix'         => '',
    // 鏁版嵁搴撹皟璇曟ā寮�
    'debug'          => true,
    // 鏁版嵁搴撻儴缃叉柟寮�:0 闆嗕腑寮�(鍗曚竴鏈嶅姟鍣�),1 鍒嗗竷寮�(涓讳粠鏈嶅姟鍣�)
    'deploy'         => 0,
    // 鏁版嵁搴撹鍐欐槸鍚﹀垎绂� 涓讳粠寮忔湁鏁�
    'rw_separate'    => false,
    // 璇诲啓鍒嗙鍚� 涓绘湇鍔″櫒鏁伴噺
    'master_num'     => 1,
    // 鎸囧畾浠庢湇鍔″櫒搴忓彿
    'slave_no'       => '',
    // 鏄惁涓ユ牸妫�鏌ュ瓧娈垫槸鍚﹀瓨鍦�
    'fields_strict'  => true,
    // 鏁版嵁闆嗚繑鍥炵被鍨� array 鏁扮粍 collection Collection瀵硅薄
    'resultset_type' => 'array',
    // 鏄惁鑷姩鍐欏叆鏃堕棿鎴冲瓧娈�
    'auto_timestamp' => false,
    // 鏄惁闇�瑕佽繘琛孲QL鎬ц兘鍒嗘瀽
    'sql_explain'    => false,
];
