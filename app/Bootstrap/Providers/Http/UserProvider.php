<?php

namespace App\Bootstrap\Providers\Http;

use App\User;
use App\Bootstrap\Providers\AbstractProvider;
use App\DataSource\User\Admin\Position\Mapper as AdminPositionMapper;
use App\Services\Auth\User\SessionDriver as Driver;

class UserProvider extends AbstractProvider
{

    public function register() : void
    {
        $this->container->singleton(User::class, function() {
            $mapper = $this->container->resolve(AdminPositionMapper::class);
            $user = $this->container->resolve(Driver::class)->authenticate();

            if ($user->isAdmin()) {
                $position = $mapper->findById($user->getAdminPosition());
            }
            else {
                $position = $mapper->create();
            }

            return $this->container->resolve(User::class, [$position, $user]);
        });
    }
}
