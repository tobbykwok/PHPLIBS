<?php

class FileCache extends ICache{
	private $_expiration = 3600;
	private $_cacheDir = './cache/';
	private $_ignore_readable_check = false;
	private $_use_hash = false;
	
	public function Get($key, $expiration = false){
		if(FALSE === $expiration){
			$expiration = $this->_expiration;
		}
		
		$file = $this->_getCacheFile($key);
		
		// include filelock
		// 判断$file是否有lock
		// 清除文件缓存状态 clearstatcache
		
		if($this->_ignore_readable_check || is_readable($file)){
			// 判断过期时间, 过期则 @unlink
			// 未过期(文件存在): @反序列化文件内容(file_get_contents($file))
		}else{
			//文件不可读
		}
		return false;
	}
	
	public function Set($key, $value){
		$file = $this->_getCacheFile($key);
		$filePath = dirname($file);
		// include filelock
		// 等待锁
		
		if(!is_dir($filePath)){
			$this->_createDir($filePath);
		}
		
		FileLock::createLock($file);
		if(!file_put_contents($file, serialize($value), LOCK_EX)){
			FileLock::removeLock($file);
			throw new Exception("could not store data in cache file");
		}
		FileLock::removeLock($file);
	}
	
	public function delete($key){
		// 获得file
		$file = $this->_getCacheFile($key);
		//@unlink
	}
	
	public function _getCacheFile($key){
		//获得缓存文件名(哈希/纯文件名)
		$tail = $this->_use_hash ? md5($key): basename($key);
		return $this->_cacheDir.dirname($key)."/".$tail;
	}
	
	
}