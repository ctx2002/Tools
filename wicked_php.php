<?php

//decode base64 string
echo file_get_contents('data://text/plain;base64,SSBsb3ZlIFBIUAo=');

/**
 * Encode data to Base64URL
 * @param string $data
 * @return boolean|string
 */
function base64url_encode($data)
{
  $b64 = base64_encode($data);

  if ($b64 === false) {
    return false;
  }
  // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
  $url = strtr($b64, '+/', '-_');
  // Remove padding character from the end of line and return the Base64URL result
  return rtrim($url, '=');
}

/**
 * Decode data from Base64URL
 * @param string $data
 * @param boolean $strict
 * @return boolean|string
 */
function base64url_decode($data, $strict = false)
{
  $b64 = strtr($data, '-_', '+/');
  return base64_decode($b64, $strict);
}

function prime($n)
{
    $end = (int)floor(sqrt($n));
    
	for($i = 2; $i <= $end; $i++) {
		
		if (($n % $i) === 0) {
			return false;
		}
	}
	
	return true;
}
/**
@param int $start
@return int
**/
function findNextPrime($start)
{
	$c = 1000000;
	while($start < $c) {
		$start++;
        if (prime($start) === true) {
			return $start;
		}		
	}
	return 1;
}

/**
@param int $dividend
@param int $divisor
@return int
**/
function qandr($dividend , $divisor)
{
	$r = gmp_div_qr((string)$dividend, (string)$divisor);
	return [gmp_intval($r[0]), gmp_intval($r[1])];
}

/**
@param int $n
@return array<int>
**/
function factorization($n)
{
    $end = (int)floor(sqrt($n));
    $start = 2;
	$results = [];
	while($start <= $end) {
		
		if (prime($n) === true) {
		    $results[] = $n;
            return $results;			
		}
		
		list($q, $r) = qandr($n, $start);
		
	    if ($r !== 0) {
			$start = findNextPrime($start);
		    if ($start === 1) {
				return [];
			}
		} else {
		    $results[] = $start;
            $n = $q;			
		}		
	}
	
	return $results;
}

//a quick function to calculate C(n,k)
//see elementary of number theory , David M Burton, problem 1.2.(b) 
function choose_o($n, $k)
{
    if ($n < 0 || $k < 0) {
		return 0;
	}
	
	if ($n == 1) {
		return 1;
	}
	
	if ($k == 0) {
		return 1;
	}
	
	if ($n == $k) {
		return 1;
	}
	
	if ($n - $k < $k) {
		$k = $n - $k;
	}

    $para = [];
	$choose2 = [];
	
	$para[]  =  ['n' => $n - 1, 'c' => $k]; //split
    $choose2[] = ['n' => $n - 1, 'c' => $k - 1]; //split
    
	$sum = 0;
    while(true) {
		
	    $item = array_pop($choose2);
        $n = $item['n'];
		$k = $item['c'];
		
		if ($k == 1) {
			$sum += $n;
			while(count($para) > 0) {
			    $oldc = array_pop($para);
			    $f = ($oldc['n'] - $oldc['c'] + 1 ) / $oldc['c'];
	            $temp = ($f * $sum);
			    $sum = $sum + $temp;
			}
			break;
		} else {
			$para[] = ['n' => $n - 1, 'c' => $k];
			$choose2[] = ['n' => $n - 1, 'c' => $k - 1];	
		}    	
	}
    return $sum;	
}

//Extended Euclidean algorithm
//return [gcd, x0, y0]
//solve equation ax + by = c
function ext_gcd($a, $b, $c)
{
	$asign = 1;
	$bsign = 1;
	if ($a < 0) {
		$asign = -1;
	}
	
	if ($b < 0) {
		$bsign = -1;
	}
	
	$r0 = abs($a);
	$r1 = abs($b);
	
	$m0 = 1;
	$n0 = 0;
	
	$m1 = 0;
	$n1 = 1;
	
	
	while (($remainer = ($r0 % $r1)) !== 0) {
	    $quotion = floor($r0 / $r1);

        $r0 = $r1;
        $r1 = $remainer;

        
		$mtemp = $m0 - $m1*$quotion;
		$m0 = $m1;
		$m1 = $mtemp;

        $ntemp = $n0 - $n1*$quotion;
        $n0 = $n1;
		$n1 = $ntemp;		
	}
	
	if ($c % $r1 !== 0) {
		throw new Exception('no integer solution,('.$a.', '.$b.') is not a factor of '.$c);
	}
	
	$multipler = $c / $r1;
	
	return [$r1, $m1*$asign*$multipler, $n1*$bsign*$multipler];
}

//sub set
/*
$set = [1, 2, 3, 4];
$result = [];
$output = [];
subset($set, $result, count($set), $output);
*/
function subset($set, $result, $len, &$output)
{
    if ($len === 0) {
        $output[] = $result;
        return $result;
    }

    subset($set, $result, $len - 1, $output);
    $e = $set[$len - 1];
    $result[]= $e;

    subset($set, $result, $len - 1, $output);
}

