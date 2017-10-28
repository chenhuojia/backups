<?php defined('API') or exit('sdfgvsdfsd');?>
<?php

/**
 * @dec 得到配置文件的配置项 & 设置某配置项
 * 
 * @param null $name
 *        	配置键名
 * @param null $value
 *        	设置时提供此值
 * @return bool mixed C('db') 或 C('version->no')
 */
function C($name = null, $value = null) {
	static $_config = array ();
	if (empty ( $_config )) {
		$_config = include_once './MinPHP/core/config.php';
	}
	if (is_null ( $name )) {
		return $_config;
	} else {
		// 设置某项的值
		if (! is_null ( $value )) {
			if (strpos ( $name, '->' )) {
				$arr = explode ( '->', $name );
				$_config [$arr [0]] [$arr [1]] = $value;
			} else {
				$_config [$name] = $value;
			}
			return true;
		}
		// 获取某项的值
		if (strpos ( $name, '->' )) {
			$arr = explode ( '->', $name );
			$tmp = $_config;
			foreach ( $arr as $v ) {
				$tmp = $tmp [$v];
			}
			return $tmp;
		}
		return $_config [$name];
	}
}
// 得到数据库连接资源
function M() {
	static $_model=null;
	if (is_null ( $_model )) {
		$db = C( 'db' );
		// 连接类型
		$type = strtolower ( $db ['linktype'] );
		$type = in_array ( $type, array (
				'pdo',
				'mysqli' 
		) ) ? $type : 'mysqli';
		C ( 'db->linktype', $type );
		// 设置数据库字符集
		$dbCharset = 'SET NAMES utf8';
		switch ($type) {
			case 'pdo' : // pdo类型连接
				try {
					$_model = new PDO ( "mysql:host={$db['host']};dbname={$db['dbname']}", "{$db['user']}", "{$db['passwd']}" );
				} catch ( PDOException $e ) {
					die ( "PDO unable to connect:" . $e->getMessage () );
				}
				// 设置数据库编码
				$_model->exec ( $dbCharset );
				break;
			
			case 'mysqli' : // mysqli类型连接
				$_model = new mysqli ( "{$db['host']}", "{$db['user']}", "{$db['passwd']}", "{$db['dbname']}" );
				if ($_model->connect_errno) {
					die ( "Mysqli unable to connect:" . $_model->connect_errno . " - " . $_model->connect_error );
				}
				// 设置数据库编码
				$_model->query ( $dbCharset );
				break;
		}
	}
	return $_model;
}

// 返回一条记录集
function find($sql) {
	switch (C ( 'db->linktype' )) {
		case 'pdo' :
			$rs = M ()->query ( $sql );
			return empty($rs)?null:$rs->fetch( PDO::FETCH_ASSOC );
			break;
		case 'mysqli' :
			$rs = M ()->query ( $sql );
			return empty($rs)?null:$rs->fetch_assoc();
			break;
	}
}

// 返回多条记录
function select($sql) {
	switch (C ( 'db->linktype' )) {
		case 'pdo' :
			$rs = M ()->query ( $sql );
			if(empty($rs)){
				return null;
			}
			$rows = array ();
			while ( $row = $rs->fetch ( PDO::FETCH_ASSOC ) ) {
				$rows [] = $row;
			}
			return $rows;
			break;
		case 'mysqli' :
			$rs = M ()->query ( $sql );
			if(empty($rs)){
				return null;
			}
			$rows = array ();
			while ( $row = $rs->fetch_assoc () ) {
				$rows [] = $row;
			}
			return $rows;
			break;
	}
}

// insert
function insert($sql) {
	switch (C ( 'db->linktype' )) {
		case 'pdo' :
			return M ()->exec ( $sql );
			break;
		case 'mysqli' :
			return M ()->query ( $sql );
			break;
	}
}
function last_insert_id() {
	$dao=M();
switch (C ( 'db->linktype' )) {
		case 'pdo' :
			return $dao->lastInsertId();;
			break;
		case 'mysqli' :
			return mysqli_insert_id($dao);
			break;
	}
}
// update
function update($sql) {
	switch (C ( 'db->linktype' )) {
		case 'pdo' :
			return M ()->exec ( $sql );
			break;
		case 'mysqli' :
			return M ()->query ( $sql );
			break;
	}
}

// 设置和获取session值
function session($key = null, $value = null) {
	if (empty($value)) {
		return isset($_SESSION [$key])?$_SESSION [$key]:null;;
	}
	$_SESSION [$key] = $value;
}

// 判断是否登录
function is_login() {
	$login_name = session ( 'login_name' );
	return empty ( $login_name ) ? false : true;
}

// 判断是否为超级管理员
function is_supper() {
	return session ( 'issupper' ) == 1 ? true : false;
}

// 跳转
function go($url) {
	$gourl = '<script language="javascript" type="text/javascript">window.location.href="' . $url . '"</script>';
	die ( $gourl );
}

// 生成url
function U($array = null) {
	if (is_null ( $array )) {
		$url = '';
	} else {
		$url = '?' . http_build_query ( $array );
		$url = str_replace ( '%23', '#', $url );
	}
	return 'index.php' . $url;
}
function send_msg($userids,$msgid){
	$sql="INSERT INTO msgstatus(userid,msgid) VALUES  ";
	foreach ($userids as $key=> $userid){
		if($key==0){
		$sql.=" ('{$userid['id']}','$msgid') ";
		continue;
		}else{
			$sql.=" ,('{$userid['id']}','$msgid') ";
		}
	}
	insert($sql);
}
// 安全过滤
function I($val) {
	if (is_array ( $val )) {
		foreach ( $val as $k => $v ) {
			$val [$k] = I ( $v );
		}
		return $val;
	} else {
		if (is_numeric ( $val )) {
			return $val;
		} else if (is_string ( $val )) {
			return htmlspecialchars ( trim ( $val ), ENT_QUOTES );
		} else {
			return $val;
		}
	}
}

// 网站基础路径baseUrl
function baseUrl() {
	$currentPath = $_SERVER ['SCRIPT_NAME'];
	$pathInfo = pathinfo ( $currentPath );
	$hostName = $_SERVER ['HTTP_HOST'];
	$protocol = strtolower ( substr ( $_SERVER ["SERVER_PROTOCOL"], 0, 5 ) ) == 'https://' ? 'https://' : 'http://';
	return $protocol . $hostName . $pathInfo ['dirname'] . "/";
}

/**
 * @dec 下载文件 指定了content参数，下载该参数的内容
 * 
 * @access public
 * @param string $showname
 *        	下载显示的文件名
 * @param string $content
 *        	下载的内容
 * @param integer $expire
 *        	下载内容浏览器缓存时间
 * @return void
 */
function download($showname = '', $content = '', $expire = 180) {
	$type = "application/octet-stream";
	// 发送Http Header信息 开始下载
	header ( "Pragma: public" );
	header ( "Cache-control: max-age=" . $expire );
	// header('Cache-Control: no-store, no-cache, must-revalidate');
	header ( "Expires: " . gmdate ( "D, d M Y H:i:s", time () + $expire ) . "GMT" );
	header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s", time () ) . "GMT" );
	header ( "Content-Disposition: attachment; filename=" . $showname );
	header ( "Content-type: " . $type );
	header ( 'Content-Encoding: none' );
	header ( "Content-Transfer-Encoding: binary" );
	die ( $content );
}
