<?php

namespace App\Commands\Game\Platform\Create;

use App\Commands\Game\Platform\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        $rules = parent::getRules($data);
        $rules['name']['required'] = $this->templates->required('name');

        return $rules;
    }


    protected function getSuccessMessage() : string
    {
        return 'Platform created successfully!';
    }
}
