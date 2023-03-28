<?php

namespace App\Commands\User\Bank\Transaction\Bill;

use App\Commands\User\Bank\Transaction\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'organization' => [
                'int' => $this->templates->invalid('organization')
            ]
        ]);
    }
}
 
