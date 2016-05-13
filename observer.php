<?php
/*
测试代码创建 UserList，并将 UserListLogger 观察者添加到其中。然后添加一个消费者，并将这一更改通知 UserListLogger。
认识到 UserList 不知道日志程序将执行什么操作很关键。可能存在一个或多个执行其他操作的侦听程序。例如，您可能有一个向新用户发送消息的观察者，欢迎新用户使用该系统。这种方法的价值在于 UserList 忽略所有依赖它的对象，它主要关注在列表更改时维护用户列表并发送消息这一工作。
此模式不限于内存中的对象。它是在较大的应用程序中使用的数据库驱动的消息查询系统的基础。
*/

interface IObserver{	//成为观察者
	function onChanged($sender, $args);
}

interface IObservable{	//可被观察的对象
	function addObserver($observer);
}

class UserList implements IObservable{
	private $_observers = array();
	
	public function addCustomer($name){
		foreach($this->_observers as $obs){
			$obs->onChanged($this, $name);
		}
	}
	
	public function addObserver($observer){
		$this->_observers[] = $observer;
	}
}

class UserListLogger implements IObserver{
	public function onChanged($sender, $args){
		echo "'$args' added to user list\n";
	}
}

$ul = new UserList();
$ul->addObserver(new UserListLogger());
$ul->addCustomer("Jack");