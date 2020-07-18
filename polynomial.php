<?php

/** 5x^2 + 4x + 1**/
function lex($polynomial)
{
    //symbol set
    //constant is float
    //power must positive integer, 
    //single variable, no more than 1 variable
    //^
    // +, -
    //start char must a contant or an variable
    $index = 0;
    /*$token = getMonomial($polynomial, $index);
    $tokens[] = $token['token'];
    $index = $token['index'];*/
    $sign = 1;
    while (isset($polynomial[$index])) {
        
        if (isSign($polynomial[$index])) {
            if ($polynomial[$index] === '-') {
                $sign = -1;
            } else {
                $sign = 1;
            }
            ++$index;
        }
        
        $token = getMonomial($polynomial, $index);
        $token['token']['sign'] = $sign;
        $tokens[] = $token['token'];
        $index = $token['index'];
    }
    return $tokens;
}

function getMonomial($polynomial, $index)
{
    $signFlag = false;
    $token = [
        'sign' => 1,
        'variable' => '',
        'contant' => 1.0,
        'power' => 0
    ];

    while (isset($polynomial[$index])) {
        if (isSign($polynomial[$index])) {
            break;
        }
        if ($polynomial[$index] === '.' || ctype_digit($polynomial[$index])) {
            //get constant
            $value = span('isFloatStr', $polynomial, $index);
            $index = $value['index'];
            if (is_numeric($value['value'])) {
                $token['contant'] = (float)$value['value'];
            }
        } else if (isCtrl($polynomial[$index])) {
            $value = span('isCtrl', $polynomial, $index);
            $index = $value['index'];
        } else if (ctype_alpha($polynomial[$index])) {
            $value = span('ctype_alpha', $polynomial, $index);
            $index = $value['index'];
            $token['variable'] = $value['value'];
        } else if ($polynomial[$index] === '^') {
            ++$index;
            $value = span('isFloatStr', $polynomial, $index);
            $index = $value['index'];
            if (is_numeric($value['value'])) {
                $token['power'] = (float)$value['value'];
            }
        }
        
    }

    return ['token' => $token, 'index' => $index];
}

function isSign($char)
{
    return $char === '-' || $char === '+';
}

function isFloatStr($char)
{
    return $char === '.' || ctype_digit($char);
}

function isCtrl($char)
{
    return ctype_cntrl($char) || ctype_space($char);
}

function span(callable $predict, $polynomial, $index)
{
    $str = '';
    while (
        isset($polynomial[$index]) &&
        $predict($polynomial[$index])
    ) {
        $str .= $polynomial[$index];
        ++$index;
    }
    return ['value' => $str, 'index' => $index];
}

function emptyTokens($tokens)
{
    return count($tokens) === 0;
}

function validVariable($tokens)
{
    $b = true;
    $variable = '';
    foreach ($tokens as $token) {
        if ($variable === '') {
            $variable = $token['variable'];
        } else {
            if ($token['variable'] !== $variable) {
                $b = false;
                break;
            }
        }        
    }
    return $b;
}

/*
$t = lex("-3x^4+0.9x^2-1");
var_dump($t);
**/
