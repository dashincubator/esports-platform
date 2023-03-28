<?php

namespace App\Bootstrap\Providers\DataSource;

use App\DataSource\User\Entity as UserEntity;
use App\DataSource\User\Admin\Position\Entity as AdminPositionEntity;
use App\Bootstrap\Providers\AbstractProvider;
use Contracts\Collections\Sequential as Collection;

class UserProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->registerAdminPositionEntityBinding();
        $this->registerUserEntityBinding();
    }


    private function registerAdminPositionEntityBinding() : void
    {
        $this->container->bind(AdminPositionEntity::class, null, [
            $this->container->resolve(Collection::class, [$this->config->get('admin.permissions')])
        ]);
    }


    private function registerUserEntityBinding() : void
    {
        $this->container->bind(UserEntity::class, null, [$this->config->get('timezones.identifiers')]);
    }
}
