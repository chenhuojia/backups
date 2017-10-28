<?php
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