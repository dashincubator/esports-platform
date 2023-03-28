<?php

namespace App\Commands\Organization;

use App\Commands\AbstractFilter as AbstractParent;

abstract class AbstractFilter extends AbstractParent
{

    protected function getRules(array $data = []) : array
    {
        return [
            'domain' => [
                'required' => $this->templates->required('domain'),
                'string' => $this->templates->string('domain')
            ],
            'name' => [
                'required' => $this->templates->required('name'),
                'string' => $this->templates->string('name')
            ],
            'paypal' => [
                'email' => $this->templates->invalid('paypal')
            ],
            'user' => [
                'int' => $this->templates->invalid('user')
            ]
        ];
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return "Organization {$action} successfully!";
    }


    public function writeDomainUnavailableMessage() : void
    {
        $this->error('Domain is already in use');
    }
}
