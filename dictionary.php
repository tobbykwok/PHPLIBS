<?php

class Dictionary{
	static private $_cache_path = 'dictionaries/';
	static private $_cache_params = [];	//KEY of 'api'
	static private $_dictionaries = []; //KEY of 'dictionaries'
	const CacheExpiration = 60;
	
	static public function _load_dictionaries(){
		if(empty(self::$_cache_params)){
			$dictionaries = require CONFIG_PATH . 'config.dictionaries.php';
			foreach($dictionaries as $dname => $dictionary){
				if(isset($dictionary['api'])){		//数据缓存
					self::$_cache_params[$dname] = $dictionary;
				}else{	//键值对
					self::$_dictionaries[$dname] = $dictionary;
				}
			}
		}
	}
	
	static public function Get($id){
		try{
			if(isset(self::$_dictionaries[$id]){	//在键值对中存在
				$data = self::$_dictionaries[$id];
			} else if(isset(self::$_cache_params[$id]) || $match){	//在api列表中存在
				$data = self::_get_cache_data($id, $match, $expiration);
			} else{
				$data = self::_get_cache_data($id, false, $expiration);
			}
		}catch(Exception $e){
			//log
		}
		
		return $data;
	}
	
	static public function GetValue($id, $key){	//只适用于dictionary型缓存, api默认返回数组
		$dict = self::Get($id);
		return isset($dict[$key])? $dict[$key] : NULL;
	}
	
	static public function SetMatchParam(&$column, &$param, $kname = 'dictionary'){
		
	}
	
	// 非键值对缓存数据, 走此方法(读filecache)
	static private function _get_cache_data($id, $match = false, $expiration = false){
		$cache = self::_cache_instance();		//Filecache(get/set)
		$key = self::_get_key($id);
		$data = $cache->get($key, $expiration);
		if(FALSE === $data || empty($data)){
			self::_generate_cache($id, $match);		//生成缓存(此处直接写入存储器)
		}
		//写入完毕后, 重新get
		return $cache->get($key);
	}
	
	static private function _generate_cache($id, $match = false){
		$params = self::_get_cache_params($id, $match);	//!!!
		if($params === false){
			throw new Exception("id: {$id} cache params not exists");
		}
		//	生成数据---------------
			//代码在此处直接生成缓存内容并写入文件
		//	---------------------------
	}
	
	static private function _get_cache_params($id, $match = false){
		if(isset(self::$_cache_params[$id])){
			$idx = $id;
		} else if($match && is_numeric($id[strlen($id) - 1]) && strpos($id, "_") !== FALSE){
			//如果match && $id最后一位为数字 && id中存在下划线
			$ids = explode("_", $id);
			$idx = str_replace($ids[count($ids) - 1], "*", $id);	//把id最后下划线后面的数字替换为*
			if(!isset(self::$_cache_params[$idx])){	//如果在api列表中不存在 返回false
				return FALSE;	//idx是结尾带星号的, id是具体的
			}
			self::$_cache_params[$id] = self::$_cache_params[$idx];	//新建了一个key(指向具体的idx->*id)
			self::$_cache_params[$id]['callback'] = str_replace("*", "", $idx); //把key的星号删掉剩下划线结尾, 放入具体的key['callback']
			//old version
			self::$_cache_params[$id]['where'] .= $ids[count($ids) - 1];	//把where补全编号
			$idx = $id;
		} else{
			$idx = 'default';
		}
		
		//最后检测一般cache是否存在想要的KEY (带数字结尾的(如果有的话))
		if(!isset(self::$_cache_params[$idx])){
			return false;
		}
		
		$params = self::$_cache_params[$idx];
		//如果配置里未指定key/value, 则默认用id=>name字段(需要数据库设计配合)
		$params['key'] = isset($params['key'])? $params['key']: 'id';
		$params['value'] = isset($params['value'])? $params['value'] : 'name';
		$params['dict_name'] = $id;
		return $params;
	}
}