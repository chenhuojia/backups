<?php
namespace Think\Session\Driver;

class Redis {
	protected $lifeTime     = 3600;
	protected $sessionName  = 'BkSession';
	protected $handle       = null;
 
    /**
     * 打开Session 
     * @access public 
     * @param string $savePath 
     * @param mixed $sessName  
     */
	public function open($savePath, $sessName) {
		$this->lifeTime     = C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') : $this->lifeTime;
		// $this->sessionName  = $sessName;
        $options            = array(
            'timeout'       => C('SESSION_TIMEOUT') ? C('SESSION_TIMEOUT') : 1,
            'persistent'    => C('SESSION_PERSISTENT') ? C('SESSION_PERSISTENT') : 0
        );
		$this->handle       = new \Redis();
        $this->handle->connect(C('REDIS_HOST'),C('REDIS_PORT'), $options['timeout']);
		return true;
	}

    /**
     * 关闭Session 
     * @access public 
     */
	public function close() {
		$this->gc(ini_get('session.gc_maxlifetime'));
		$this->handle->close();
		$this->handle  = null;
		return true;
	}

    /**
     * 读取Session 
     * @access public 
     * @param string $sessID 
     */
	public function read($sessID) {
        $res=$this->handle->get($this->sessionName.$sessID);
        return $res ?$res : '';
	}

    /**
     * 写入Session 
     * @access public 
     * @param string $sessID 
     * @param String $sessData
     */
	public function write($sessID, $sessData) {
        $this->handle->set($this->sessionName.$sessID, $sessData,$this->lifeTime);
		return true;	}

    /**
     * 删除Session 
     * @access public 
     * @param string $sessID 
     */
	public function destroy($sessID) {
        $this->handle->delete($this->sessionName.$sessID);
		return true;
	}

    /**
     * Session 垃圾回收
     * @access public 
     * @param string $sessMaxLifeTime 
     */
	public function gc($sessMaxLifeTime) {
		return true;
	}
}
