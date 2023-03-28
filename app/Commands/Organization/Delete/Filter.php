<?php

namespace App\Commands\Organization\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('organization'),
                'required' => $this->templates->required('organization')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Organization deleted successfully!';
    }
}
