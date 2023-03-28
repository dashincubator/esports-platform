<?php

namespace App\Commands\User\Membership\Purchase;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'days' => [
                'int' => $this->templates->invalid('membership days'),
                'required' => $this->templates->required('membership days')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage(int $days = 0, string $text = '') : string
    {
        return "{$text} ({$days} Days) has been purchased and applied to your account successfully";
    }
}
