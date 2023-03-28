<?php

namespace App\Commands\User\Bank\Deposit\Valid;

use App\Commands\User\Bank\Deposit\AbstractFilter;

class Filter extends AbstractFilter
{

    public function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'organization' => [
                'int' => $this->templates->invalid('organization'),
                'required' => $this->templates->required('organization')
            ]
        ]);
    }
}
