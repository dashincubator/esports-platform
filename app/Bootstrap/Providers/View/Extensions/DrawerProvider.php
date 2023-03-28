<?php

namespace App\Bootstrap\Providers\View\Extensions;

use App\User;
use App\Bootstrap\Providers\AbstractProvider;
use App\View\Extensions\Drawer;

class DrawerProvider extends AbstractProvider
{

    public function register() : void
    {
        $drawers = $this->container->resolve(Drawer::class);

        $this->container->singleton(Drawer::class, function() use ($drawers) {
            $user = $this->container->resolve(User::class);

            foreach (array_merge(
                $this->config->get('view.extensions.drawers.global'),
                $this->config->get('view.extensions.drawers.' . ($user->isGuest() ? 'guest' : 'user'))
            ) as $drawer) {
                $drawers->add($drawer);
            }

            return $drawers;
        });
    }
}
