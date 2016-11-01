<?php
//name.
//document root

function commandLine()
{
    global $argv;
	$options = $argv;
	$line = function (&$item, $key)
	{
		$temp = array();
		$temp['orgi'] = array($key=>$item);
		$temp['error'] = '';
		$temp['answer'] = array();
		
		if ($key != 0) {
			$part = explode("=",$item);
			if (FALSE === $part) {
                $temp['error'] = "explode returns false, command line parameter is wrong.";
			} else {
				if (!isset($part[1])) {
					$temp['error'] = "wrong format";
				    $item = $item;
				} else {
					$temp['answer'] = array($part[0] => $part[1]);
					$item = $temp;
				}
			}
		}
		
		$item = $temp;
	};
	
    $parse = function ($options) use ($line){
		array_walk($options,$line);
		return $options;
	};
	
	$ops = $parse($options);
	return $ops;
}

function outputTemplate()
{
	//function makeFile();
	//function saveTemplate();
	//function symbolLink();
}

$ops = commandLine();
var_dump($ops);