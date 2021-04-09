<?php

function partial($func, ...$args): Closure
{
    return static function() use($func, $args) {
        return call_user_func_array($func, array_merge($args, func_get_args()));
    };
}

function identity ($value) { return $value; };

function compose(...$funcs)
{
    return array_reduce($funcs, static function($init, $func){
        return static function ($input) use ($init, $func) {
            return $func($init($input) );
        };
    }, 'identity');
}

function add3($a, $b, $c)
{
    return $a + $b + $c;
}

function square($a)
{
    return $a * $a;
}

function double($a)
{
    return $a * 2;
}

$sum = compose('double','square');
//$sum = partial('add3', 1);
var_dump($sum(1));