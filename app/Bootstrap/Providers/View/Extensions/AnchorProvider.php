<?php

namespace App\Bootstrap\Providers\View\Extensions;

use App\User;
use App\Bootstrap\Providers\AbstractProvider;
use App\View\Extensions\Anchor;

class AnchorProvider extends AbstractProvider
{

    public function register() : void
    {
        $drawers = $this->container->resolve(Anchor::class);

        $this->container->singleton(Anchor::class, function() use ($drawers) {
            $user = $this->container->resolve(User::class);

            foreach (array_merge(
                $this->config->get('view.extensions.anchors.global'),
                $this->config->get('view.extensions.anchors.' . ($user->isGuest() ? 'guest' : 'user'))
            ) as $drawer) {
                $drawers->add($drawer);
            }

            return $drawers;
        });
    }
}
