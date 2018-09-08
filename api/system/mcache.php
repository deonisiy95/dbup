<?php

// Класс для работы с memCache
// Настройки подклчюения лежат в /private/main.php
// 23.08.2017


class mCache {

	protected $obj		= null;
	protected $_local	= [];


	// model singleton
	public static function Init() {
		if (isset($GLOBALS['memcache_data'])) {
			return $GLOBALS['memcache_data'];
		}

		//
		$GLOBALS['memcache_data']	= new mCache(MCACHE_HOST,MCACHE_PORT);
		return $GLOBALS['memcache_data'];
	}

	//
	public static function End() {
		$class = __CLASS__;
		if (isset($GLOBALS['memcache_data']) && $GLOBALS['memcache_data'] instanceof $class) {
			$GLOBALS['memcache_data']->close();
		}

		$GLOBALS['memcache_data']	= null;
		unset($GLOBALS['memcache_data']);
	}

	//
	function __construct($host,$port) {
		$this->obj = new Memcache;
		$this->obj->connect($host,$port);
	}

	//
	function get($key,$default=false){
		$key	= self::_getKey($key);

		$local	= $this->_getLocal($key);
		if ($local != false) {
			return $local;
		}

		$output	=  $this->obj->get($key);
		$this->_setLocal($key, $output);

		return $output == false ? $default : $output;
	}

	//
	function delete($key){
		$key	= self::_getKey($key);
		$this->obj->set($key,false,MEMCACHE_COMPRESSED,1);
		$this->_local[$key]	= false;
	}

	//
	function set($key,$value, $expire=3600){
		$key	= self::_getKey($key);

		if ($expire > time()) {
			$expire = $expire - time();
		}

		if (is_int($value)) {
			$value = strval($value);
		}

		$this->obj->set($key,$value,MEMCACHE_COMPRESSED,$expire);
		$this->_setLocal($key, $value);
	}

	// закрываем соединение
	public function close() {
		$this->obj->close();
	}

	// -------------------------------------------------
	// PROTECTED
	// -------------------------------------------------

	//
	protected function _getLocal($key) {
		$info	= isset($this->_local[$key]) ? $this->_local[$key] : false;

		if ($info == false) {
			return false;
		}

		if (isset($info['expire']) && $info['expire'] > time()) {
			return $info['data'];
		}

		return false;
	}

	//
	protected function _setLocal($key,$value) {
		$info	= array(
			'expire'	=> time() + 5,
			'data'		=> $value,
		);

		$this->_local[$key]	= $info;
	}

	// формируем ключ
	protected function _getKey($key) {
		return md5(PATH_ROOT . CODE_UNIQ_VERSION . $key);
	}

}