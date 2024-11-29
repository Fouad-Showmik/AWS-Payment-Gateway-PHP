<?php

class CreditCard
{
    public $number;
    public $expiry_month;
    public $expiry_year;
    public $cvv;

    public function __construct($number, $expiry_month, $expiry_year, $cvv)
    {
        $this->number = $number;
        $this->expiry_month = $expiry_month;
        $this->expiry_year = $expiry_year;
        $this->cvv = $cvv;
    }

    // Add methods for credit card validation and processing
}
