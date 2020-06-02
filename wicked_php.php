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

