<?php

namespace Home\Common;


class Tools
{
    /**
     * app发送短信验证码记录
     * @param $mobile
     * @param $code
     * @param $session_id
     */
    public function sms_log($mobile,$code,$session_id){
        //判断是否存在验证码
        $data = M('sms_log')->where(array('mobile'=>$mobile,'session_id'=>$session_id))->order('id DESC')->find();
        //获取时间配置
        $sms_time_out = tpCache('sms.sms_time_out');
        $sms_time_out = $sms_time_out ? $sms_time_out : 120;
        //120秒以内不可重复发送
        if($data && (time() - $data['add_time']) < $sms_time_out)
            return array('status'=>-1,'msg'=>$sms_time_out.'秒内不允许重复发送');
        $row = M('sms_log')->add(array('mobile'=>$mobile,'code'=>$code,'add_time'=>time(),'session_id'=>$session_id));
        if(!$row)
            return array('status'=>-1,'msg'=>'发送失败');
        //$send = sendSMS($mobile,'您好，你的验证码是：'.$code);
        $send = sendSMS($mobile,$code);
        return array('status'=>1,'msg'=>'发送成功');
        if(!$send)
            return array('status'=>-1,'msg'=>'发送失败');
        return array('status'=>1,'msg'=>'发送成功');
    }

    /**
     * 短信验证码验证
     * @param $mobile   手机
     * @param $code  验证码
     * @param $session_id   唯一标示
     * @return bool
     */
    public function sms_code_verify($mobile,$code,$session_id){
        //判断是否存在验证码
        $data = M('sms_log')->where(array('mobile'=>$mobile,'session_id'=>$session_id,'code'=>$code))->order('id DESC')->find();
        if(empty($data))
            return array('status'=>-1,'msg'=>'手机验证码不匹配');

        //获取时间配置
        $sms_time_out = tpCache('sms.sms_time_out');
        $sms_time_out = $sms_time_out ? $sms_time_out : 120;
        //验证是否过时
        if((time() - $data['add_time']) > $sms_time_out)
            return array('status'=>-1,'msg'=>'手机验证码超时'); //超时处理
        M('sms_log')->where(array('mobile'=>$mobile,'session_id'=>$session_id,'code'=>$code))->delete();
        return array('status'=>1,'msg'=>'验证成功');
    }
    
    /**
     * 发送验证码
     * @param $sender 接收人
     * @param $type 发送类型
     * @return json
     */
    public function send_validate_code($sender,$type){
        $sms_time_out = tpCache('sms.sms_time_out');
        $sms_time_out = $sms_time_out ? $sms_time_out : 180;
        //获取上一次的发送时间
        $send = session('validate_code');
        if(!empty($send) && $send['time'] > time() && $send['sender'] == $sender){
            //在有效期范围内 相同号码不再发送
            $res = array('status'=>-1,'msg'=>'规定时间内,不要重复发送验证码');
        }
        $code =  mt_rand(1000,9999);
        if($type == 'email'){
            //检查是否邮箱格式
            if(!check_email($sender))
                $res = array('status'=>-1,'msg'=>'邮箱码格式有误');
            $send = send_email($sender,'验证码','您好，你的验证码是：'.$code);
        }else{
            if(!check_mobile($sender))
                $res = array('status'=>-1,'msg'=>'手机号码格式有误');
                //$send = sendSMS($sender,'您好，你的验证码是：'.$code);
                $send = sendSMS($sender,$code);
        }
        if($send){
            $info['code'] = $code;
            $info['sender'] = $sender;
            $check['is_check'] = 0;
            $info['time'] = time() + $sms_time_out; //有效验证时间
            session('validate_code',$info);
            $res = array('status'=>1,'msg'=>'验证码已发送，请注意查收');
        }else{
            $res = array('status'=>-1,'msg'=>'验证码发送失败,请检查');
        }
        exit(json_encode($res));
    }
    
    public function check_validate_code($code,$sender){     
        $check = session('validate_code');
        if(empty($check))
        {
            $res = array('status'=>0,'msg'=>'请先获取验证码');
        }elseif($check['time']<time())
        {
            $res = array('status'=>0,'msg'=>'验证码已超时失效');
        }elseif($code!=$check['code'] || $check['sender']!=$sender)
        {
            $res = array('status'=>0,'msg'=>'验证失败,验证码有误');
        }else
        {
            $check['is_check'] = 1; //标示验证通过
            session('validate_code',$check);
            $res = array('status'=>1,'msg'=>'验证成功');
        }
        exit(json_encode($res));
    }

} 