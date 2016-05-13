<?php
function array_array(){
	$a = array(1,123, 2, 3, 4, 5);
	$b = array("one", "two", "three", "four", "five");
	$c = array("uno", "dos", "tres", "cuatro", "cinco");
	$d = array("333");

	$d = array_map(null, $a, $b, $c, $d);
	print_r($d);
}

