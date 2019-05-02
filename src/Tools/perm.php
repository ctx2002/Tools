<?php

//this is a non recusive algo to permutate all 
//T,F ,logic assignment.
//for example:
//noRec(2), outputs
//FF
//FT
//TF
//TT

//here is how this Algo generate all 'T' and 'F'.
//first , give a 'F' , generate a string according to $number paramter.
// says, $number is 4, so $str is 'FFFF'
// add $str to $collection. and now $collection contains 'FFFF'.

// we start from postion 3 ($number - 1), and looping the $collection.
// at position 3, str has F, duplicate that string, and 
// change postion 3 'F' to 'T', and add new string to collection.
// now $collection contains 'FFFF', and 'FFFT'

// start from postion 2, and looping the $collection again.
// for 'FFFF' , we get 'FFTF', for 'FFFT', we get 'FFTT',
// and add those 2 strings to $collection.
// now $collection contains 4 items, 'FFFF','FFFT','FFTF', 'FFTT'
// 
// start from postion 1, and looping the $collection again.
// start from postion 0, and looping the $collection again.

//if no more new item to add, we exist loop.
// if (in_array($temp, $collection)) used to check if all permutations are added.
function noRec($number)
{
	$collection = [];
	$str = str_repeat('F', $number);
	
	
	$collection[] = $str;
	$index = $number - 1;
	
	while($index > -1) {
		$size = count($collection);
		
		for($j = 0; $j < $size; $j++) {
			$temp = $collection[$j];
			if ( $temp[$index] = 'F' ) {
				$temp[$index] = 'T';
			} else {
				$temp[$index] = 'F';
			};
			
			if (in_array($temp, $collection)) {//generated all permutations
				break 2;
			} else {
				array_push($collection, $temp);
			}
		}
		
		--$index;
	}
	
	return $collection;
	
}

$coll = noRec(3);
echo "total: " . count($coll);
echo "\n";

foreach ($coll as $value) {
	echo $value . "\n";
}

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
