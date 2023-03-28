<?php

namespace App\View\Extensions;

class Ordinal
{

    private $suffix = [
        'th','st',
        'nd','rd',
        'th','th',
        'th','th',
        'th','th'
    ];


    public function __invoke(int $number)
    {
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
           return 'th';
        }

        return $this->suffix[$number % 10];
    }
}
