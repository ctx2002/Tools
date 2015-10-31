<?php
namespace Tools\Tax;

trait NZGst
{
    public function calculate($amount)
    {
        return $amount * 1.15;
    }
}
