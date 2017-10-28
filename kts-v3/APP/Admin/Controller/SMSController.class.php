<?php

/**
 * 短信设置
 * @author qiu
 *
 */
namespace Admin\Controller;
use Common\Controller\AdminController;
class SMSController extends AdminController {
	/**
     * 短信设置列表
     */
    public function smsList($n = '15')
    {
            $where['config_type'] = 100;
	        $this->sms = M('config')   
	               ->field('config_id,config_name,config_field,config_value,config_value_type,config_type')
	               ->where($where)
	               ->select();
            $this->display();
    }
    /**
     * 编辑短信设置
     */
    public function smsEdit($config_id='0')
    {
        if (IS_POST)
        {
            $config = M('config');
            if (!$config->create($_POST,2))
                $this->error($config->getError());
            $config_id = $config->where('config_id = '.$config_id)->save($_POST);
            if($config_id !== false){
                $this->addLog('config_name='.I('config_name').'&config_name='.$config_id,1);// 记录操作日志
                $this->success('编辑成功', U('Admin/SMS/smsList'));
            }else{
                $this->addLog('config_name='.I('config_name').'&config_name='.$config_id,0);// 记录操作日志
                $this->error('编辑失败');
            }
        }
        else
        {
            $where['config_type'] = 100;
            $where['config_id'] = $config_id;
	        $this->sms = M('config')   
	               ->field('config_id,config_name,config_field,config_value,config_value_type,config_type')
	               ->where($where)
	               ->find();
            $this->display();
        }
    }

}