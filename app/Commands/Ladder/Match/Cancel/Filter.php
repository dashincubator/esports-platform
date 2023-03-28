<?php

namespace App\Commands\Ladder\Match\Cancel;

use App\Commands\Ladder\Match\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        return [
            'id' => [
                'int' => $this->templates->invalid('match'),
                'required' => $this->templates->required('match')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage(bool $wager = false) : string
    {
        $message = 'Match canceled successfully!';

        if ($wager) {
            $message .= ' We have begun to process a refund for your wager match.';
        }

        return $message;
    }
}
