<?php
namespace Tools\Functions;

/**
 * this function has same interface as filter_var_array,
 * but you can supply nested array as data and definition.
 * 
 * @link http://php.net/manual/en/function.filter-var-array.php
 * 
 * 
 * @param array $data same as filter_var_array first argument
 * @param array $args same as filter_var_array second argument
 * @param boolean $add_empty same as filter_var_array third argument
 * **/
function filter_vals($data, $args, $add_empty = true)
 {
	$result = null;
	foreach ($args as $key => $value) {
       
        if (is_array($value)) {
            if (!isset($value['filter'])) {
                $result[$key] = my_print_1($value, $data[$key]);
            } else {
                $result[$key] = extractValue($key, $data, $value, $add_empty);
            }
        } else if(!isset($data[$key])) {
            $result[$key] = array($key => null); 
        } else {
            $result[$key] = extractValue($key, $data, $value, $add_empty);
        }
	 
    }
	return $result;
 }
 
 function extractValue($key, $data, $value, $add_empty)
 {
    $r = filter_var_array(array($key => $data[$key]), array($key => $value), $add_empty);
    if(is_array($r)) {
	return $r[$key];
    }
    return $r;
 }

