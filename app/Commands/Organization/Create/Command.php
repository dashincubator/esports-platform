<?php

namespace App\Commands\Organization\Create;

use App\Commands\AbstractCommand;
use App\Commands\User\AdminPosition\Create\Command as CreateCommand;
use App\Commands\User\Update\Admin\Command as UpdateCommand;
use App\DataSource\Game\Mapper as GameMapper;
use App\DataSource\Organization\{Entity, Mapper as OrganizationMapper};

class Command extends AbstractCommand
{

    private $mapper;


    public function __construct(CreateCommand $create, Filter $filter, GameMapper $game, OrganizationMapper $organization, UpdateCommand $update)
    {
        $this->command = compact('create', 'update');
        $this->filter = $filter;
        $this->mapper = compact('game', 'organization');
    }


    protected function run(string $domain, string $name, ?string $paypal, ?int $user) : Entity
    {
        $organization = $this->mapper['organization']->create(compact($this->filter->getFields()));

        if (!$this->mapper['organization']->isUniqueDomain($organization->getDomain())) {
            $this->filter->writeDomainUnavailableMessage();
        }

        if (!$this->filter->hasErrors()){
            $this->filter->writeSuccessMessage();
            $this->mapper['organization']->insert($organization);

            if ($user) {
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

            return $organization;
        }

        return $this->mapper['organization']->create();
    }
}
