<?php

namespace App\Commands\Ladder\Gametype\Delete;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('gametype'),
                'required' => $this->templates->required('gametype')
            ]
        ];
    }


    public function writeGametypeInUseMessage() : void
    {
        $this->error('
            Gametype cannot be deleted, it was used in at least one ladder match.
            The ladder and all matches must be deleted before removing this gametype.
        ');
    }


    protected function getSuccessMessage() : string
    {
        return 'Gametype deleted successfully!';
    }
}
