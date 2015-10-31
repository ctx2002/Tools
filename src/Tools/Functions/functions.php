<?php
namespace Tools\Functions;

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

