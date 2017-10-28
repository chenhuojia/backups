 <?php

function message($no, $message, $data = "")
{
    return array(
        'error' => $no,
        'message' => $message,
        'data' => $data
    );
}

function GetRedisConn(int $db=1)
{
    static $handle = array(
        'version' => - 1
    );
    if ($handle['version'] == $db) {
        return $handle['redis'];
    } else {
        if (! isset($handle['redis'])) {
            $handle['redis'] = new \Redis();
            $handle['redis']->pconnect(C('REDIS_HOST'), C('REDIS_PORT'), C('redis_time_out'));
        }
        $handle['version'] = $db;
        $handle['redis']->select($db);
    }
    return $handle['redis'];
}

/**
 * 检测用户是否已经登录
 */
function is_login()
{
    $user = session('user_id');
    if (empty($user)) {
        return false;
    } else {
        return true;
    }
}

/**
 * 用户登录时 开启 本次登录session,只有在没有token 还没有token时使用
 * 
 * @param unknown $toekn            
 */
function start_session($token)
{
    if (empty($token)) {
        E('token不能为空');
    }
    session_id($token);
    session_start();
}

function _session()
{
    // 获取token
    $token = get_token($token = 'token');
    if (! empty($token)) {
        session_id($token);
        session_start();
    }
}

function get_token($token)
{
    $toke = isset($_REQUEST[$token]) ? $_REQUEST[$token] : null;
    return $toke;
}

function check_myself($user_id, $book_id, $type = 1, $shop_id = 0)
{
    switch ($type) {
        case 1:
            $result = M('book')->where(array(
                'book_id' => $book_id,
                'user_id' => $user_id
            ))->find();
            break;
        case 2:
            $result = 0;
            $shop = M('shop')->find($shop_id);
            if ($shop['user_id'] == $user_id) {
                $result = M('shop_books')->where(array(
                    'book_id' => $book_id,
                    'shop_id' => $shop_id
                ))->find();
            }
            break;
    }
    return $result;
}

?>