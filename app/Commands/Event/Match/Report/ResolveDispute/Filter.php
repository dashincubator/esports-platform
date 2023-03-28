<?php

namespace App\Commands\Event\Match\Report\ResolveDispute;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'match' => [
                'int' => $this->templates->invalid('match'),
                'required' => $this->templates->required('match')
            ],
            'placements' => [
                'array' => $this->templates->invalid('placements list'),
                'required' => $this->templates->required('placements list')
            ],
            'placements.*.placement' => [
                'int' => $this->templates->invalid('placements list'),
                'required' => $this->templates->required('placements list')
            ],
            'placements.*.team' => [
                'int' => $this->templates->invalid('placements list'),
                'required' => $this->templates->required('placements list')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Match reports updated successfully! Match status will be updated in a few minutes.';
    }


    public function writeCannotReportMessage() : void
    {
        $this->error("You cannot report this match if it's complete, upcoming, or recently created");
    }


    public function writeInvalidPlacementsMessage() : void
    {
        $this->error("The scores you reported are invalid!");
    }
}
