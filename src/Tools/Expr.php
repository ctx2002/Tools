class Expr
{
	private $left;
	private $right;
	private $op;
	public function __construct()
	{
		$this->left = null;
		$this->right =null;
	}
	public function addLeft($expr)
	{
		if ($this->left instanceof Expr) {
		    $this->left->addLeft($expr);
		} else {
			$this->left = $expr;
		}
	}
	
	public function addRight($expr)
	{
		if ($this->right instanceof Expr) {
			$this->right->addRight($expr) ;
		} else {
			$this->right = $expr;
		}
	}
	
    public function addOp($op)
	{
		$this->op = $op;
	}
	
	public function getOp()
	{
		return $this->op;
	}
	
	public function getLeft()
	{
		return $this->left;
	}
	
	public function getRight()
	{
		return $this->right;
	}
}

function evaluate($expr, $obj)
{
	$left = $expr->getLeft();
	$right = $expr->getRight();
	$value1 = null;
	$value2 = null;
	
	if ( $left instanceof Closure) {
		$value1 = $left($obj);
	}
	
	if ( $right instanceof Closure) {
		$value2 = $right($obj);
	}
	
	if ( $left instanceof Expr) {
		$value1 = evaluate($left, $obj);
	}
	
	if ( $right instanceof Expr) {
		$value2 = evaluate($right, $obj);
	}
	
	$op = $expr->getOp();
	if ($op == '==') {
		return $value1 == $value2;
	} else if ($op == 'and') {
		return $value1 && $value2;
	} else if ($op == 'or') {
		return $value1 || $value2;
	} else if ($op == '!=') {
		return $value1 != $value;
	} else if ($op instanceof Closure) {
		return $op($value1, $value2);
	}
}

class ExprBuilder
{
	
	protected function compareField($fieldName, $value, $op)
	{
		$ex = new Expr();
		$ex->addOp($op);
		$ex->addLeft(function($obj) use ($fieldName)  {
			return $obj->{$fieldName};
		});

		$ex->addRight(function($obj) use ($value) {
			return $value;
		});
		return $ex;
	}
	public function eq($fieldName, $value)
	{
		return $this->compareField($fieldName, $value, "==");
	}
	
	public function neq($fieldName, $value)
	{
		return $this->compareField($fieldName, $value, "!=");
	}
	
	public function andX($expr1, $expr2)
	{
		return $this->compareX($expr1, $expr2, "and");
	}
	
	public function orX($expr1, $expr2)
	{
		return $this->compareX($expr1, $expr2, "or");
	}
	
	public function customX($expr1, $expr2, Closure $op)
	{
		return $this->compareX($expr1, $expr2, $op);
	}
	
	protected function compareX($expr1, $expr2, $op)
	{
		$expr = new Expr();
		$expr->addOp($op);
		$expr->addLeft($expr1);
		$expr->addRight($expr2);
		return $expr;
	}
}



class External
{
	private $collection;
	public function __construct(){
		$this->collection = array();
	}
	public function __destruct(){
		$this->collection = null;
	}
	
	public function add($obj)
	{
		$this->collection[] = $obj;
	}
	
    //use ExprBuilder to build up $criteria
	public function match(Expr $criteria)
	{		
		return array_filter($this->collection,function($item) use ($criteria) {
			return evaluate($criteria, $item);
		});
	}
}

$obj = new A();
$obj->setName("anru");
$obj->setTel("9870");

$c = new A();
$c->setName("chen");
$c->setTel("1234");

$col = array($obj, $c);

$b = new ExprBuilder();
$ex1 = $b->eq("name","anru");
$ex2 = $b->eq("tel","9870");
$ex3 = $b->andX($ex1,$ex2);

foreach ($col as  $o) {
	$r = evaluate($ex3, $o);
	if ($r) {
		var_dump($obj);
	}	
}
