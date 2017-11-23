<?php
class PNGFirst8Bytes
{
	/*The first eight bytes of a PNG file always contain the following (decimal) values:

    137 80 78 71 13 10 26 10*/
	private $resource;
	private $header = [];
	public function __construct($resource)
	{
		$this->resource = $resource;
	}
	
	public function get()
	{
		if (empty( $this->header)) {
			for($i =0; $i < 8; $i++) {
				$c = fgetc($this->resource);
				if ($c === FALSE) {
					throw new \Exception("unable to read png header's first 8 bytes");
				} else {
					$this->header[] = ord($c);
				}
			}
		}
		
		return $this;
	}
	
	public function check()
	{
		$expected = [137 ,80 ,78 ,71 ,13 ,10 ,26 ,10];
		$diff = array_diff_assoc($expected, $this->header);
		if (is_array($diff) && empty($diff)){
			return true;
		}
		return false;
	}
	
	public function getFirst8Bytes()
	{
		return $this->header;
	}
}

class FileFormatSignatureChecker
{
	/** @param resource $handler ***/
	public static function png($handler)
	{
		$check = new PNGFirst8Bytes($handler);
		return $check->get()->check();
	}
}

function check($handler)
{
	$length = 8;
	$m = pngMagicNumber();
	$n = pngFile($handler);
	
	for($i = 0; $i < 8; $i++) {
		if ($n->current() != $m->current() ){
			return false;
		}
		$n->next();
		$m->next();
	}
	
	return true;
}

function pngFile($handler)
{
	for($i = 0; $i < 8; $i++) {
		$c = fgetc($handler);
		if ($c === FALSE) {
			throw new \Exception("unable to read png header's first 8 bytes");
		} else {
			yield ord($c);
		}
	}
}

function pngMagicNumber()
{
	$expected = [137 ,80 ,78 ,71 ,13 ,10 ,26 ,10];
	foreach ($expected as $value) {
		yield $value;
	}
}

$h = fopen("C:\Users\Anru Chen\Pictures\org_partner.png", "r");
//$b = FileFormatSignatureChecker::png($h);
$b = check($h);
var_dump($b);
fclose($h);

$binarydata = pack("nvc*", 0x1234, 0x5678, 65, 66);
$len = strlen($binarydata);
for($i=0; $i<$len; $i++) {
	printf("%d\n",bin2hex($binarydata[$i]));
	//echo bin2hex($binarydata[$i])."\n";
}
