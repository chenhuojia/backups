<?php 
class Session_Redis{
    /**
     * @var string 键前缀
     */
    const CACHE_KEY = 'SESSION:';

    /**
     * @var object 数据库对象
     */
    private $_cache = null;

    /**
     * 开始Session
     *
     * @return void
     */
    public function __construct($cache) {
        $this->_cache = $cache;
        $this->_lifetime = ini_get('session.gc_maxlifetime');
        session_set_save_handler(
            array($this, '_open'),
            array($this, '_close'),
            array($this, '_read'),
            array($this, '_write'),
            array($this, '_destroy'),
            array($this, '_gc')
        );
    }

    // _open
    public function _open($savePath, $name) {
        return true;
    }

    // _close
    public function _close() {
        $this->_gc($this->_lifetime);
        return true;
    }

    // _read
    public function _read($id) {
        $res = $this->_cache->get(self::CACHE_KEY . $id);
        return $res ? $res : '';
    }

    // _write
    public function _write($id, $value) {
        if ($value) {
            $flag = $this->_cache->set(self::CACHE_KEY . $id, $value);
            return $flag;
        } else {
            return FALSE;
        }
    }

    // _destroy
    public function _destroy($id) {
        $flag = $this->_cache->delete(self::CACHE_KEY . $id);
        return $flag;
    }

    // _gc
    public function _gc($lifetime) {
    }
}
$redis=new \Redis();
$redis->connect('127.0.0.1',6379, 100);
new Session_Redis($redis);
session_start();
$_SESSION['qiu']=1234;
