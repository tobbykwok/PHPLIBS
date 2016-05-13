<?php
/*
策略模式。
在此模式中，算法是从复杂类提取的，因而可以方便地替换。
例如，如果要更改搜索引擎中排列页的方法，则策略模式是一个不错的选择。
思考一下搜索引擎的几个部分 —— 
一部分遍历页面，一部分对每页排列，另一部分基于排列的结果排序。
在复杂的示例中，这些部分都在同一个类中。
通过使用策略模式，您可将排列部分放入另一个类中，以便更改页排列的方式，而不影响搜索引擎的其余代码。


UserList 类是打包名称数组的一个包装器。
它实现 find 方法，该方法利用几个策略之一来选择这些名称的子集。
这些策略由 IStrategy 接口定义，该接口有两个实现：一个随机选择用户，另一个根据指定名称选择其后的所有名称。运行测试代码时，将得到以下输出：
*/
interface IStrategy{
	function filter($record);
}

class FindAfterStrategy implements IStrategy
{
	private $_name;
	
	public function __construct($name){
		$this->_name = $name;
	}
	
	public function filter($record){
		return strcmp($this->_name, $record) <= 0;
	}
}

class RandomStrategy implements IStrategy
{
	public function filter($record){
		return rand(0,1) >=0.5;
	}
}

class UserList
{
	private $_list = array();
	
	public function __construct($names){
		if($names != null){
			foreach($names as $name){
				$this->_list[] = $name;
			}
		}
	}
	
	public function add($name){
		$this->_list[] = $name;
	}
	
	public function find($filter){
		$recs = array();
		foreach($this->_list as $user){
			if($filter->filter($user)){
				$recs[] = $user;
			}
		}
		return $recs;
	}
}

$ul = new UserList(["Andy", "Smart", "Jack", "Lori", "Megan"]);
$f1 = $ul->find(new FindAfterStrategy("J"));
print_r($f1);

$f2 = $ul->find(new RandomStrategy());
print_r($f2);