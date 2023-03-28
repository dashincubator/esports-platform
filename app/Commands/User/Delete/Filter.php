<?php

namespace App\Commands\User\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Account deleted successfully!';
    }


    public function writeLeaveAllTeamsMessage() : void
    {
        $this->error('All teams must leave all teams before deleting this account');
    }
}
