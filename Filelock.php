<?php

class FileLock{
	static function isLocked(){
		
	}
	
	static function createLock($filename){
		// @touch file
	}
	
	static function removeLock($fileName){
		// @unlink
	}
	
	static function waitForLock($fileName){
		// clearstatcache
		// sleep
		// isLocked -> removeLock
	}
}