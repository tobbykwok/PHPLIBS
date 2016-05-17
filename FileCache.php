<?php
include_once "./FileHelper.php";
include_once "./ICache.php";

class FileCache extends FileHelper implements ICache{
	const FILE_LOCK_FILENAME = 'FileLock.php';
	
	private $_expiration = 3600;
	private $_cacheDir = './cache/';
	private $_ignore_readable_check = false;
	private $_use_hash = false;
	
	private static $use_igbinary = false;
	
	public function __construct($cacheDir = "", $expiration = 3600, $useHash = true){
		include './'.self::FILE_LOCK_FILENAME;
		if(!empty($cacheDir)){
			$this->set_cache_dir($cacheDir);
		}
		$this->_expiration = $expiration;
		$this->_use_hash = $useHash;
		$this->_ignore_readable_check = !is_readable(__FILE__);
		
		if(TRUE === $use_igbinary){
			method_exists("igbinary_serialize")
		}
	}
	
	public function set_cache_dir($dir){
		if(@is_dir($dir)){
			if($dir[strlen($dir) - 1] != '/'){
				$dir .= '/';
			}
			$this->_cacheDir = $dir;
		} else{
			throw new Exception("Not dir");
		}
	}
	
	public function Get($key, $expiration = false){
		if(FALSE === $expiration){
			$expiration = $this->_expiration;
		}
		
		$file = $this->_getCacheFileName($key);
		
		// 判断$file是否有lock
		// 清除文件缓存状态 clearstatcache
		if(FileLock::isLocked($file)){
			FileLock::waitForLock($file, TRUE);
		}
		
		clearstatcache();
		
		if(file_exists($file) && ($this->_ignore_readable_check || is_readable($file))){
			// 判断过期时间, 过期则 @unlink
			// 未过期(文件存在): @反序列化文件内容(file_get_contents($file))
			if((time() - @filemtime($file) > $expiration)){
				@unlink($file);
				return false;
			} else{
				return @_unserialize(file_get_contents($file));
			}
		}else{
			//文件不可读
			Logger::write("Cannot read or not exist: ".$file);
		}
		return false;
	}
	
	public function Set($key, $value){
		$file = $this->_getCacheFileName($key);
		$filePath = dirname($file);
		
		if(FileLock::isLocked($file)){
			FileLock::waitForLock($file);
		}

		if(!file_exists($filePath) || !is_dir($filePath)){
			$this->create_dir($filePath);
		}
		
		FileLock::createLock($file);
		if(!file_put_contents($file, $this->_serialize($value), LOCK_EX)){
			FileLock::removeLock($file);
			throw new Exception("could not store data in cache file");
		}
		FileLock::removeLock($file);
	}
	
	public function delete($key){
		$file = $this->_getCacheFileName($key);
		//@unlink
		if(FileLock::isLocked($file)){
			FileLock::waitForLock($file);
		}
		return !(!@unlink($file));
	}
	
	// returns filename
	public function _getCacheFileName($key){
		$tail = $this->_use_hash ? md5($key): basename($key);
		$filename = $this->_cacheDir;
		if(!empty(dirname($key)) && dirname($key) != '.'){
			$filename .= dirname($key). '/';
		}
		$filename .= $tail;
		return $filename;
	}
	//---- 可实现更多的序列化方法, 套设计模式
	private function _serialize($content){
		if(TRUE === self::$use_igbinary){
			
		}else{
			return serialize($content);
		}
	}
	
	private function _unserialize($content){
		if(TRUE === self::$use_igbinary){
			
		}else{
			return unserialize($content);
		}
	}
}

$cache = new FileCache();

$start = microtime(true);
//$cache->Set("names", "1,2,3,4");
//var_dump($cache->Get("names"));
ini_set("set_time_limit", 0);
for($i = 0; $i < 5000; $i++){
	$cache->Set(rand(1,200000), rand(2,333333));
}

var_dump(microtime(true) - $start);