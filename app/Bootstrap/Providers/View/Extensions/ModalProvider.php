<?php

namespace App\Bootstrap\Providers\View\Extensions;

use App\User;
use App\Bootstrap\Providers\AbstractProvider;
use App\View\Extensions\Modal;

class ModalProvider extends AbstractProvider
{

    public function register() : void
    {
        $modals = $this->container->resolve(Modal::class);

        $this->container->singleton(Modal::class, function() use ($modals) {
            $user = $this->container->resolve(User::class);

            foreach (array_merge(
                $this->config->get('view.extensions.modals.global'),
                $this->config->get('view.extensions.modals.' . ($user->isGuest() ? 'guest' : 'user'))
            ) as $modal) {
                $modals->add($modal);
            }

            return $modals;
        });
    }
}
