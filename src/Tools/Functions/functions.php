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
                $result[$key] = filter_vals($value, $data[$key], $add_empty);
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

/***
 * example:
 * 
 * $data = array(
    'product_id'    => 'libgd<script>',
    'component'     => '10',
    'versions'      => '2.0.33',
    'testscalar'    => array( "test_test" => array('2', '23', '10', '12')),
    'testarray'     => '2',
	'data' => array(
	    "name" => array("first" => "mike", "last" => "gru")
	),
	
	'test1' => array(
	    "hello" => array("world" => array("nz" => 'new zealand', 'au' => 'aus'), "last" => "chen")
	)
);

$args = array(
    'product_id'   => FILTER_SANITIZE_ENCODED,
    'component'    => array('filter'    => FILTER_VALIDATE_INT,
                            //'flags'     => FILTER_FORCE_ARRAY, 
                            'options'   => array('min_range' => 1, 'max_range' => 10)
                           ),
    'versions'     => FILTER_SANITIZE_ENCODED,
    'doesnotexist' => FILTER_VALIDATE_INT,
    'testscalar'   => array(
                            "test_test" => array( 'filter' => FILTER_VALIDATE_INT,
                            'flags'  => FILTER_REQUIRE_SCALAR)
                           ),
    'testarray'    => array(
                            'filter' => FILTER_VALIDATE_INT,
                            //'flags'  => FILTER_FORCE_ARRAY,
                           ),
					
    'data' => array(
       'name' => array(
	       'first' => array(
                            'filter' => FILTER_SANITIZE_ENCODED,
                            'flags'  => FILTER_FORCE_ARRAY,
                           ),
		   'last' => FILTER_SANITIZE_ENCODED
	   )
    ),
	
	'test1' => array(
       'hello' => array(
	       'world' => array('nz' => FILTER_SANITIZE_ENCODED , 'au' => array( 'filter' => FILTER_SANITIZE_ENCODED )),
		   'last' =>  array(
                            'filter' => FILTER_SANITIZE_ENCODED,
                            'flags'  => FILTER_FORCE_ARRAY,
                           )
	   )
    )

);
 * 
 * $v = filter_vals($data, $args)
   var_export($v);
 * 
 * *****/

