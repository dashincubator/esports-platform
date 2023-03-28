<?php

namespace App\Bootstrap\Providers;

use App\Organization;
use App\DataSource\Organization\{Entity, Mapper};
use App\Bootstrap\Providers\AbstractProvider;

class OrganizationProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->singleton(Organization::class);

        $this->container->when(Organization::class)
            ->needs(Entity::class)
            ->give(function() {
                return $this->container->resolve(Mapper::class)->findByDomain($this->config->get('app.host'));
            });
    }
}
