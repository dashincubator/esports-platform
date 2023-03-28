<?php

namespace App\Commands\Ladder\Match\Start;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('match'),
                'required' => $this->templates->required('match')
            ]
        ];
    }


    public function writeInvalidMatchErrorMessage() : void
    {
        $this->error('Match is not upcoming');
    }
}
