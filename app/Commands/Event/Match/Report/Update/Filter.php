<?php

namespace App\Commands\Event\Match\Report\Update;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('match report'),
                'required' => $this->templates->required('match report')
            ],
            'placement' => [
                'int' => $this->templates->invalid('placement'),
                'required' => $this->templates->required('placement')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Reported Successfully';
    }


    public function writeCannotReportMessage() : void
    {
        $this->error("You cannot report this match if it's complete, upcoming, or recently created");
    }


    public function writeUnauthorizedMemberMessage() : void
    {
        $this->error('You do not have permission to manage matches for this team');
    }
}
