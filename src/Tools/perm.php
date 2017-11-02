<?php

function perm($str,$num)
{
	$count = strlen($str) - 1;
	$stack = [];
	$stack[]= $str;
	
	while($count > 0) {
	
		$num = count($stack);
		
		for ($i = 0; $i < $num; $i++) {
			$pop = array_shift($stack);
			array_push($stack, $pop);
			
			if ($pop[$count] == 'T') {
				$pop[$count] = 'F';
			} else {
				$pop[$count] = 'T';
			}
				
			array_push($stack, $pop);
		}
		
		--$count;	
	}
	
	return $stack;
	
}

function generateStr($char, $number)
{
	return str_repeat($char, $number);
}

$str = generateStr("T", 5);
$all = perm($str, 5);

$str = generateStr("F", 5);
$all = perm($str, 5);
