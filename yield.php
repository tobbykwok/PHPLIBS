<?php
/* 生成器 */

function gen_one2three(){
	for($i = 1; $i <= 3; $i++){
		yield $i;
	}
}

$generator = gen_one2three();

foreach($generator as $val){
	echo "$val";
}
