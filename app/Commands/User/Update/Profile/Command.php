<?php

namespace App\Commands\User\Update\Profile;

use App\Commands\AbstractCommand;
use App\Commands\User\Update\Accounts\Command as AccountsCommand;
use App\Commands\User\Upload\{Avatar\Command as AvatarCommand, Banner\Command as BannerCommand};
use App\DataSource\User\Mapper;
use Contracts\Upload\File;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(AccountsCommand $accounts, AvatarCommand $avatar, BannerCommand $banner, Filter $filter, Mapper $mapper)
    {
        $this->command = compact('accounts', 'avatar', 'banner');
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(
        ?array $accounts,
        ?File $avatar,
        ?File $banner,
        ?string $bio,
        ?string $email,
        int $id,
        ?string $timezone,
        ?bool $wagers
    ) : bool
    {
        $user = $this->mapper->findById($id);

        if ($user->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif (is_string($email) && $email !== $user->getEmail() && !$this->mapper->isUniqueEmail($email)) {
            $this->filter->writeEmailUnavailableMessage();
        }
        elseif ($accounts) {
            $this->delegate($this->command['accounts'], compact('accounts', 'id'));
        }

        if (!$this->filter->hasErrors()) {
            foreach (['avatar', 'banner'] as $key) {
                if (!${$key}) {
                    continue;
                }

                ${$key} = $this->delegate($this->command[$key], [
                    'default' => $user->{'get' . ucfirst($key)}(),
                    'file' => ${$key},
                    'name' => $id
                ]);
            }
        }

        if (!$this->filter->hasErrors()) {
            $user->fill(compact($this->filter->getFields(['accounts', 'id'])));
            $this->mapper->update($user);
        }

        return $this->booleanResult();
    }
}
