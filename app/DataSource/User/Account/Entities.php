<?php

namespace App\DataSource\User\Account;

use App\DataSource\AbstractEntities;

class Entities extends AbstractEntities
{

    public function createdAfter(int $time) : bool
    {
        foreach ($this->entities as $entity) {
            if ($entity->getCreatedAt() < $time) {
                continue;
            }

            return true;
        }

        return false;
    }


    public function findByName(string $name) : AbstractEntities
    {
        return $this->filter(function(Entity $account) use ($name) {
            return $account->getName() === $name;
        });
    }


    public function findByUser(int $user) : AbstractEntities
    {
        return $this->filter(function(Entity $account) use ($user) {
            return $account->getUser() === $user;
        });
    }


    public function findByValue(string $value) : AbstractEntities
    {
        return $this->filter(function(Entity $account) use ($value) {
            return $account->getValue() === $value;
        });
    }


    public function getFirstValue() : string
    {
        foreach ($this->entities as $entity) {
            if (!$entity->getValue()) {
                continue;
            }

            return $entity->getValue();
        }

        return '';
    }


    public function matches(array $accounts) : bool
    {
        $original = [];

        foreach ($this as $entity) {
            $original[$entity->getName()] = $entity->getValue();
        }

        return hash_equals(md5(json_encode($original)), md5(json_encode($accounts)));
    }
}
