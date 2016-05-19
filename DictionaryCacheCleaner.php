<?php

class DictionaryCacheCleaner{
	private $_dictionaries = array();
	private $_indexs = [
		'INSERT' => 2,
		'UPDATE' => 1,
		'DELETE' => 2,
	];
	
	public function __construct(){
		$params = \Dictionary::GetCacheParams();
		
		foreach($params as $name=>$param){
			if(isset($param['table_name'])){
				$this->_dictionaries[$param['table_name']][$name] = $param;
			}
		}
	}
	
	public function doing(&$sql){
		$parts = explode(' ', $sql, 4);
		$event = $parts[0];
		//找到是否是_indexs中的某种查询方式
		if(!isset($this->_indexs[$event])){
			return false;
		}
		$tabname = str_replace('`','',$parts[$this->_indexs[$event]);
		
		if(isset($this->_dictionaries[$tabname])){
			$this->_clear($tabname, $event, $sql);
		}
	}
	
	private function _clear(&$tabname, &$event){
		foreach($this->_dictionaries[$tabname] as $name => $conf){
			//log
			Logger::write("ClearCache: ".$name);
			\Dictionary::ClearCache($name);
		}
	}
}