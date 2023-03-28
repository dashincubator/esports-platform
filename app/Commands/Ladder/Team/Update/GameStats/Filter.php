<?php

namespace App\Commands\Ladder\Team\Update\GameStats;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'ladder' => [
                'int' => $this->templates->invalid('ladder'),
                'required' => $this->templates->required('ladder')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return "Expired stats updated successfully!";
    }
}
