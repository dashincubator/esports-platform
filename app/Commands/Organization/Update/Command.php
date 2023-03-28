<?php

namespace App\Commands\Organization\Update;

use App\Commands\AbstractCommand;
use App\Commands\User\Update\Admin\Command as UpdateCommand;
use App\DataSource\Game\Mapper as GameMapper;
use App\DataSource\Organization\{Entity, Mapper as OrganizationMapper};

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(Filter $filter, GameMapper $game, OrganizationMapper $organization, UpdateCommand $update)
    {
        $this->command = compact('update');
        $this->filter = $filter;
        $this->mapper = compact('game', 'organization');
    }


    protected function run(string $domain, int $id, string $name, ?string $paypal, ?int $user) : Entity
    {
        $organization = $this->mapper['organization']->findById($id);

        if ($organization->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            $organization->fill(compact('domain'));

            if (!$this->mapper['organization']->isUniqueDomain($organization->getDomain(), $organization->getId())) {
                $this->filter->writeDomainUnavailableMessage();
            }

            if (!$this->filter->hasErrors()){
                if ($user && $user !== $organization->getUser()) {

                    // Wipe Old User Position
                    if ($organization->getUser()) {
                        $this->delegate($this->command['update'], [
                            'editor' => 0,
                            'adminPosition' => 0,
                            'users' => [$organization->getUser()]
                        ]);
                    }

                    // Create Position
                    $position = $this->delegate($this->command['create'], [
                        'games' => $this->mapper['game']->findAll()->column('id'),
                        'name' => 'Site Owner',
                        'organization' => $organization->getId(),
                        'permissions' => [
                            'manageAdmin',
                            'manageAdminPositions',
                            'manageBankWithdraws',
                            'manageGames',
                            'manageLadders'
                        ]
                    ]);

                    // Set User As Admin Using New Position
                    if (!$position->isEmpty()){
                        $this->delegate($this->command['update'], [
                            'editor' => 0,
                            'adminPosition' => $position->getId(),
                            'users' => [$user]
                        ]);
                    }
                }

                $organization->fill(compact($this->filter->getFields(['id'])));
            }
        }

        if (!$this->filter->hasErrors()){
            $this->filter->writeSuccessMessage();
            $this->mapper['organization']->update($organization);

            return $organization;
        }

        return $this->mapper['organization']->create();
    }
}
