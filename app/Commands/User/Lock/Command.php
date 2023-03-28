<?php

namespace App\Commands\User\Lock;

use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\Lock\Message\Mapper as LockMessageMapper;

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(Filter $filter, LockMessageMapper $message, UserMapper $user)
    {
        $this->filter = $filter;
        $this->mapper = compact('message', 'user');
    }


    protected function run(array $content, int $id) : bool
    {
        $user = $this->mapper['user']->findById($id);

        if ($user->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }

        if (!$this->filter->hasErrors()) {
            $user->lock();
            $this->mapper['user']->update($user);

            $message = $this->mapper['message']->create([
                'content' => $content,
                'user' => $user->getId()
            ]);

            $this->mapper['message']->insert($message);
        }

        return $this->booleanResult();
    }
}
