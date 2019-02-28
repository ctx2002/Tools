<?php
/*Money,
Currency,
SumMoney,
ProductMoney,
Converter
*/

// [ ["USD", 10], ["NZD", 5] ]

class Money
{
	public $currency;
	public $value;
	public function __construct(string $currency, $value)
	{
		$this->currency = $currency;
		$this->value = $value;
	}
	
	public function eq(Money $money) : bool
	{
		if ($this->currency === $money->currency) {
			return $this->value == $money->value;
		}
		return false;
	}
}

class MoneyExpression
{
	static public function add(Money $baseMoney, Money $money)
	{
	    $obj = new Money("HGF",0.00);
		$obj->currency = $baseMoney->currency;
		$obj->value = $baseMoney->value + $money->value;
		return $obj;
	}
}

class Currency
{
	protected $sum;
	
	public function __construct(Money $baseMoney)
	{
		$this->sum = $baseMoney;
	}
	
	public function add(Money $money)
	{
		if ($this->sum->currency === $money->currency) {
			$this->sum = MoneyExpression::add($this->sum, $money);
		} else {
			//1. convery money to base money;
			//2. adding money;
			$con = Converter::con($this->sum, $money);
			$this->sum = MoneyExpression::add($this->sum, $con);
		}
		
		return $this;
	}
	
	public function sum()
	{
		return $this->sum;
	}
}

class Converter
{
	static public function con(Money $baseMoney, Money $source)
	{
		//1 usd is 1.47 nzd
		$data =  ["USD" => 
		                 ["NZD" => 1.47,
						  "AUD" => 1.41]];
		$rate = $data[$baseMoney->currency][$source->currency];
		$result = round( $source->value / $rate, 2);
		return new Money($baseMoney->currency, $result);
	}
}

////////
$source = [ ["USD", 10], ["NZD", 5] ];

$currency = new Currency(new Money("USD", 0.00));
$obj = $currency->add(new Money($source[0][0], $source[0][1]))->add(new Money($source[1][0], $source[1][1]))->sum();
var_dump($obj);
