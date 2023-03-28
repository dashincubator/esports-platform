<?php

namespace App\Services\Auth\User;

use App\DataSource\User\{Entity, Mapper};
use Contracts\Session\Session;
use Exception;

class SessionDriver
{

    private $mapper;

    private $session;


    public function __construct(Mapper $mapper, Session $session)
    {
        $this->mapper = $mapper;
        $this->session = $session;
    }


    public function authenticate() : Entity
    {
        $guest = $this->mapper->create();

        // Invalid Session Data
        if (!$this->session->has('user.id') || !$this->session->has('user.signin')) {
            return $guest;
        }

        $user = $this->mapper->findById($this->session->get('user.id'));

        // Prevent Uneccessary Wiping Of Session On Every Page Load If User Is Guest
        if ($user->isEmpty()) {
            return $guest;
        }

        // Credentials Don't Match Wipe Session
        if (!$user->isValidSignin($this->session->get('user.signin'))) {
            $this->signout($user);

            return $guest;
        }

        return $user;
    }


    public function signin(Entity $user) : void
    {
        if ($user->isEmpty()) {
            throw new Exception("User Does Not Exist, Cannot Complete Signin");
        }

        $this->session->regenerate(false);
        $this->session->set('user', [
            'id' => $user->getId(),
            'signin' => $user->signin()
        ]);

        $this->mapper->update($user);
    }


    public function signout() : void
    {
        $this->session->regenerate();
    }
}
