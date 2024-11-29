<?php

class AmountsDecimal
{
    public static function format($amount)
    {
        return number_format($amount, 2, '.', '');
    }
}
