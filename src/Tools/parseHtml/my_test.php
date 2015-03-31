<?php

if (isset($_POST['url']) ) {
    $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_ENCODED);
	$parser = new htmlParser(urldecode($url) );
	$data = $parser->run();
	$json = json_encode($data);
	file_put_contents("films.json",$json);
	echo $json;
}

////////////////////////////////////////////////////////
class htmlParser {
    private $url;
	//private $root;
	public function __construct($url) {
	    $this->url = $url;
	}
	
	public function run() {
	    $html = $this->clean();
        $root = $this->getRoot($html);
        return $this->constructTree($root);		
	}
	
	protected function clean() {
	    $config = array(
           'indent'         => true,
           'output-html'   => true,
           'wrap'           => 200);

		$tidy = new tidy;
		
		$opts = array(
		  'http'=>array(
			'method'=>"GET",
			'header'=>"Accept-language: en\r\n" .
					  "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"
 
		  )
		);
		
		//$text = file_get_contents($this->url);
		$text = $this->getContents();
		//var_dump($text,$this->url);
		$tidy->parseString($text, $config, 'utf8');
		$tidy->cleanRepair();
		$node = $tidy->html();
		return $node->value;	
	}
	
	private function getContents() {
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$f = curl_exec($ch);

		// close cURL resource, and free up system resources
		curl_close($ch);
		return $f;
		
	}
	
	protected function getRoot($htmlString) {
	    $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = FALSE;
        $text = $dom->loadHTML($htmlString);
        return $dom->documentElement;           
	}
	
	protected function constructTree($root) {
	    $obj = array();
	    $att = array();
		
		foreach ($root->attributes as $attribute) {
			$att[$attribute->name] =  $attribute->value;		
		}
	
	
		if (!empty($att)) {
		    //loading attributes to element ($root->nodeName)
			$obj[$root->nodeName]['attributes']  = $att;
		} 

        foreach ($root->childNodes as $childNode) {
			if ($this->hasChild($childNode) ) {
				$name = $this->getChildNodeName($childNode);
				$result = $this->constructTree($childNode);
				$obj[$root->nodeName]['child_nodes'][$name] = $result[$childNode->nodeName];    
			} else if ($childNode->nodeType == XML_ELEMENT_NODE) {
				$result = $this->constructTree($childNode); 
				$obj[$root->nodeName]['child_nodes'][$childNode->nodeName] = $result[$childNode->nodeName]; 
			} else if ($childNode->nodeType == XML_CDATA_SECTION_NODE || $childNode->nodeType == XML_TEXT_NODE) {
				
				if (trim($childNode->wholeText) != '') {
					$obj[$root->nodeName]['value'] = $childNode->wholeText;
				}
			}
	    }
	
	    return $obj;		
	}
	
	private function getChildNodeName($node) {
		foreach ($node->attributes as $attribute) {
			if ($attribute->name == "id") return $node->nodeName . "#" . $attribute->value;	
		}

		return $node->nodeName;    
	}

	private function hasChild($node) {
	    /**
		   DOMDocument treats text node as child node, but we only need to 
		   treat a tag as child node. etc, <a> <img> ... 
		**/
		if ($node->hasChildNodes()) {
			foreach ($node->childNodes as $child) {
				if ($child->nodeType == XML_ELEMENT_NODE) {
					return true;
				}
			}
		}
		
		return false;
	}
}
/*
$htmlString = getCleanHtml($url);
//echo $htmlString;
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->preserveWhiteSpace = FALSE;
$text = $dom->loadHTML($htmlString);
$root = $dom->documentElement;

$list = constructTree($root);
echo json_encode($list);

function getCleanHtml($url) {
    $config = array(
           'indent'         => true,
           'output-html'   => true,
           'wrap'           => 200);

	// Tidy
	$tidy = new tidy;
	$tidy->parseString(file_get_contents($url), $config, 'utf8');
	$tidy->cleanRepair();
	$node = $tidy->html();
	return $node->value;    
}

function constructTree($root) {
    $obj = array();
	$att = array();
	foreach ($root->attributes as $attribute) {
	    $att[$attribute->name] =  $attribute->value;		
	}
	
	
	if (!empty($att)) {
	    $obj[$root->nodeName]['attributes']  = $att;
	}

	
	foreach ($root->childNodes as $childNode) {
	    if (hasChild($childNode) ) {
		    $name = getChildNodeName($childNode);
			$result = constructTree($childNode);
		    $obj[$root->nodeName]['child_nodes'][$name] = $result[$childNode->nodeName];    
		} else if ($childNode->nodeType == XML_ELEMENT_NODE) {
			$result = constructTree($childNode); 
		    $obj[$root->nodeName]['child_nodes'][$childNode->nodeName] = $result[$childNode->nodeName]; 
		} else if ($childNode->nodeType == XML_CDATA_SECTION_NODE || $childNode->nodeType == XML_TEXT_NODE) {
		    
			if (trim($childNode->wholeText) != '') {
		        $obj[$root->nodeName]['value'] = $childNode->wholeText;
			}
		}
	}
	
	return $obj;
}

function getChildNodeName($node) {
    foreach ($node->attributes as $attribute) {
        if ($attribute->name == "id") return $node->nodeName . "#" . $attribute->value;	
	}

    return $node->nodeName;    
}

function hasChild($node) {
    if ($node->hasChildNodes()) {
	    foreach ($node->childNodes as $child) {
		    if ($child->nodeType == XML_ELEMENT_NODE) {
			    return true;
			}
		}
	}
	
	return false;
}
*/
