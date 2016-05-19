<?php

class DBListener{
	//通过钩子拦截框架大部分组件操作
	private $_cleaner;
	private $_logger;
	
	public function __construct(){
		//load private variablies
	}
	
	public function afterQuery($event, $connection){
		// 得到执行的sql, sqlvariables
		// bindSqlParams
		// do log
		// 判断当前执行的sql是否符合清除缓存的条件->do clean
		
	}
	
	public function bindSqlParams(&$sql, &$params){
		if(empty($params)){
			return ;
		}
		// 目的是将params整合到sql中, 以便log记录完整的sql语句
		// 两种前缀 ? / :
		if(strpos($sql, "?") === false){
			$sql = str_replace("?", ";%s'",$sql);
			array_ushift($params, $sql);
			$sql = call_user_func('sprintf', $params);
		} else{
			$prefix = (strpos(key($params), ':') === 0)? '':':';
			$keys = [];
			$vals = [];
			foreach($params as $key => $value){
				$keys[] = $prefix.$k;
				$vals[] = "'".$value."'";
			}
			$sql = str_replace($keys, $vals, $sql);
			
		}
	}
	}
	
	
}