<?php
/*
���Դ��봴�� UserList������ UserListLogger �۲�����ӵ����С�Ȼ�����һ�������ߣ�������һ����֪ͨ UserListLogger��
��ʶ�� UserList ��֪����־����ִ��ʲô�����ܹؼ������ܴ���һ������ִ�����������������������磬��������һ�������û�������Ϣ�Ĺ۲��ߣ���ӭ���û�ʹ�ø�ϵͳ�����ַ����ļ�ֵ���� UserList ���������������Ķ�������Ҫ��ע���б����ʱά���û��б�������Ϣ��һ������
��ģʽ�������ڴ��еĶ��������ڽϴ��Ӧ�ó�����ʹ�õ����ݿ���������Ϣ��ѯϵͳ�Ļ�����
*/

interface IObserver{	//��Ϊ�۲���
	function onChanged($sender, $args);
}

interface IObservable{	//�ɱ��۲�Ķ���
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