<?php

namespace App\Commands\Game\Platform\Update;

use App\Commands\Game\Platform\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'id' => [
                'int' => $this->templates->invalid('platform'),
                'required' => $this->templates->required('platform')
            ]
        ]);
    }


    protected function getSuccessMessage() : string
    {
        return 'Platform updated successfully!';
    }
}
