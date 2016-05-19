<?php
class Logger{
	static private $_formats = [];
	static private $_time_format = 'Ymd-H:i:s[O]';
	
	const LOG_FORMAT_STANDARD = '[%s][%s][%s]%s';
	const LOG_FORMAT_KEYVALUE = '[%s]%s';
	const LOGGER_FLAG_1 = '[ %s ]';
	
	public static function hasFormat($formatName){
		return array_key_exists($formatName, self::$_formats);
	}
	
	public static function setFormat($formatName, $format_string){
		if(FALSE === self::hasFormat($formatName)){
			self::$_formats[$formatName] = $format_string;
			return TRUE;
		}
		return FALSE;
	}
	
	public static function getFormat($formatName){
		return array_key_exists[$formatName]? self::$_formats[$formatName] : NULL;
	}
	
	public static function listFormats(){
		return self::$_formats;
	}
	
	public static function getTypeString($type){
		return array_keys(self::$_formats);
	}
	
	public static function write($message, $priority = LOG_INFO){
		return syslog($priority, $message);
	}
	
	public static function writeFormat($formatName, $params = [], $priority = LOG_INFO){
		//$message = call_user_func_array("sprintf", array_merge([self::$_formats[$formatName]], array_values($params)));
		$sprintf_args = array_values($params);
		array_unshift($sprintf_args, self::$_formats[$formatName]);
		$message = call_user_func_array("sprintf", $sprintf_args);
		return ((FALSE === $message)? FALSE : self::write($message, $priority));
	}
	
	public static function writeFlag($message, $flag = self::LOGGER_FLAG_1){
		$message = sprintf(self::LOGGER_FLAG_1, $message);
		return ((FALSE === $message)? FALSE : self::write(self::_log_time() . $message, LOG_DEBUG));
	}
	
	public static function timeFormat($formatString){
		self::$_time_format = $formatString;
	}
	
	private static function _log_time(){
		return date(self::$_time_format, time());
	}
}

Logger::setFormat("abc", "wiwiwiw%d");
var_dump(Logger::listFormats());
var_dump(Logger::writeFormat("abc", [123321]));
Logger::writeFlag("flag");
