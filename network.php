<?php

var_dump(checkdnsrr('baidu.com'));

$long = ip2long('192.0.34.166');
var_dump($long);

printf("%u".PHP_EOL, $long);	//转换为无符号地址
