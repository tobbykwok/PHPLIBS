<?php
define('FLUSH_COUNT',5000);

function http_request($url, $method='GET', $data=[], &$header='X-Requested-With: XMLHttpRequest'){
	$options = [
		'http'=>[
			'method'	=> strtoupper($method),
			'header'	=> $header,
		]
	];
	
	if(!empty($data)){
		$queryString = http_build_query($data);
		if($method == 'POST'){
			$options['http']['content'] = $queryString;
		}
		else{
			$url .= '?'.$queryString;
		}
	}
	
	$context = stream_context_create($options);
	$result = file_get_contents($url, FALSE, $context);
	var_dump($options);
	var_dump($url);
	return $result;
}

function file_write_line($handle, $string){
	return fwrite($handle, $string . PHP_EOL);
}

function random_numbers_file($filename, $count = 100, $append = TRUE, $min = 0, $max = 999999){
	$file_handle = NULL;
	$file_mode = file_exists($filename)? ($append? 'a' : 'x') : ($append? 'w': 'a');
	$file_handle = fopen($filename, $file_mode);

	$_cache = [];
	while($count-- > 0){
		$_cache[] = rand($min, $max);
		if($count % FLUSH_COUNT === 0 || $count === 0){
			fwrite($file_handle, implode(PHP_EOL, $_cache));
			($count === 0) ? : fwrite($file_handle, PHP_EOL);
			unset($_cache);
		}
	}
}

function random_numbers_file_Carray($filename, $count = 100, $append = FALSE, $min = 0, $max = 9999){
	$file_handle = NULL;
	$file_mode = file_exists($filename)? ($append? 'a' : 'w') : ($append? 'a': 'x');
	$file_handle = fopen($filename, $file_mode);
	if(!$file_handle) return;

	$_cache = [];
	fwrite($file_handle, "int randoms[] = {");
	while($count-- > 0){
		$_cache[] = rand($min, $max);
		if($count % FLUSH_COUNT === 0 || $count === 0){
			fwrite($file_handle, implode(',', $_cache));
			unset($_cache);
		}
	}
	fwrite($file_handle, "};");
}

$s = microtime(true);
random_numbers_file_Carray($argv[1], $argv[2]);
echo microtime(true) - $s;