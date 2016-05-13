<?php
$h = "localhost";
$p = 80;
$errno;
$errstr;
$timeout = 10;
$message='';
$result = '';

try{
	$fp = fsockopen($h, $p, $errno, $errstr, $timeout);
}
catch(Exception $e){
	echo "Exception: {$e->getMessage()}".PHP_EOL;
	exit;
}

if(!$fp){
	$result = "Connect failed";
}else{
	fwrite($fp, $message);
	//$a = fgets($fp);
	fputs($fp, "END");
	fclose($fp);
	$result = trim($result);
	$result = substr($result, 2);
}