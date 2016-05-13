<?php

var_dump(gd_info());
exit();
header("Content-Type: text/html; charset='utf-8'");

$exec = isset($_GET['exec'])? $_GET['exec']:'';
if(empty($exec)){
	$returnArr = [];
	$returnCode = '';
	exec('dir',$returnArr, $returnCode);
}else{
	$returnArr = [];
	$returnCode = '';
	exec($exec,$returnArr, $returnCode);
}
echo "ReturnCode is {$returnCode}";
$result = [];
foreach($returnArr as $k=>&$v){
	if(empty(trim($v))){
		continue;
	}
	$result[$k] = iconv('gb2312', 'utf-8', $v);
}

var_dump($result);
//echo iconv('gb2312', 'utf-8', $output);

/* $returnArr = [];
$returnCode = '';
exec('dir',$returnArr, $returnCode);

echo "ReturnCode is {$returnCode}";
var_dump($returnArr);
 */
