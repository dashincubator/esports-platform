<?php

namespace App\Commands\Event\Team\Create;

use App\Commands\AbstractFilter;

class Filter extends AbstractFilter
{

    protected function getRules(array $data = []) : array
    {
        $name = $data['name'] ?? '';

        return [
            'event' => [
                'int' => $this->templates->invalid('event'),
                'required' => $this->templates->required('event')
            ],
            'name' => [
                'max:20' => $this->templates->max('name', $name, 20),
                'min:3' => $this->templates->min('name', $name, 3),
                'required' => $this->templates->required('name'),
                'string' => $this->templates->string('name')
            ],
            'user' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Team created successfully!';
    }


    public function writeAlreadyOnTeamMessage() : void
    {
        $this->error('You are already on a team for this event');
    }


    public function writeNameUnavailableMessage() : void
    {
        $this->error('Team name is already in use');
    }


    public function writeRegistrationClosedMessage() : void
    {
        $this->error('Registration is closed');
    }
}
