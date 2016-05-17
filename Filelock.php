<?php
include_once "./FileHelper.php";

class FileLock extends FileHelper{
	private static $_lock_extends = '.lock';
	const FILE_LOCK_WAITFORLOCK = 250;
	const FILE_LOCK_RETRIES = 20;
	
	static function isLocked($filename){
		return file_exists($filename.self::$_lock_extends);
	}
	
	static function createLock($filename){
		$dir = dirname($filename);
		if(!is_dir($dir)){
			self::create_dir($dir, 0755);
		}
		@touch($filename.self::$_lock_extends);
	}
	
	static function removeLock($filename){
		@unlink($filename.self::$_lock_extends);
	}
	
	static function waitForLock($filename, $cleanall = FALSE){
		$cnt = 0;
		do{
			clearstatcache();
			usleep(self::FILE_LOCK_WAITFORLOCK);
			$cnt ++;
		} while($cnt <= FILE_LOCK_RETRIES && self::isLocked($filename));
		
		// unlock forcely
		if(self::isLocked($filename)){
			self::removeLock($filename);
		}
		if(TRUE === cleanall){
			@unlink($filename);
		}
	}
}