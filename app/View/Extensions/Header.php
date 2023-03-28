<?php

namespace App\View\Extensions;

class Header
{

    private $modifiers = [
        'black' => [
            'header' => '',
            'children' => 'black'
        ],
        'white' => [
            'header' => '',
            'children' => 'white'
        ]
    ];

    private $use = 'black';


    public function getModifiers() : array
    {
        return $this->modifiers[$this->use];
    }


    public function useBlack() : void
    {
        $this->use = 'black';
    }


    public function useWhite() : void
    {
        $this->use = 'white';
    }
}
