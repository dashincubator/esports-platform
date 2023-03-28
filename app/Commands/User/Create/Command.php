<?php

namespace App\Commands\User\Create;

use App\Organization;
use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper;

class Command extends AbstractCommand
{

    private $mapper;

    private $organization;


    public function __construct(Filter $filter, Mapper $mapper, Organization $organization)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
        $this->organization = $organization;
    }


    protected function run(string $email, string $password, string $timezone, string $username) : bool
    {
        $user = $this->mapper->create(array_merge(compact($this->filter->getFields()), [
            'organization' => $this->organization->getId()
        ]));

        if (!$this->mapper->isUniqueSlugAndUsername($user->getSlug(), $user->getUsername())) {
            $this->filter->writeUsernameUnavailableMessage();
        }
        elseif (!$this->mapper->isUniqueEmail($user->getEmail())) {
            $this->filter->writeEmailUnavailableMessage();
        }

        if (!$this->filter->hasErrors()){
            $this->mapper->insert($user);
        }

        return $this->booleanResult();
    }
}
