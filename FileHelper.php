<?php
class FileHelper {

	public static function create_dir($dirname, $mode=0755){
		if(!file_exists($dirname)){
			self::create_dir(dirname($dirname));
			var_dump("before: ".$dirname);
			mkdir($dirname, $mode);
		}
	}
}