<?php

namespace App\Commands\Event\Team\Member\Update;

use App\Commands\Event\Team\Member\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return array_merge(parent::getRules($data), [
            'managesMatches' => [
                'int' => $this->templates->invalid('match permission'),
                'required' => $this->templates->required('match permission')
            ],
            'managesTeam' => [
                'int' => $this->templates->invalid('team permission'),
                'required' => $this->templates->required('team permission')
            ]
        ]);
    }


    protected function getSuccessMessage() : string
    {
        return 'Team roster updated successfully!';
    }
}
