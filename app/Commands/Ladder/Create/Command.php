<?php

namespace App\Commands\Ladder\Create;

use App\Organization;
use App\Commands\AbstractCommand;
use App\DataSource\Ladder\{Entity, Mapper};

class Command extends AbstractCommand
{

    private $mapper;

    private $organization;


    public function __construct(Filter $filter, Mapper $mapper, Organization $organization)
    {
        $this->filter = $filter;
        $this->mapper = $mapper;
        $this->organization = $organization;
    }


    protected function run(
        int $endsAt,
        float $entryFee,
        ?array $entryFeePrizes,
        ?int $firstToScore,
        ?string $format,
        int $game,
        array $gametypes,
        int $maxPlayersPerTeam,
        ?bool $membershipRequired,
        int $minPlayersPerTeam,
        string $name,
        ?float $prizePool,
        ?array $prizes,
        ?bool $prizesAdjusted,
        ?array $rules,
        ?string $slug,
        int $startsAt,
        ?int $stopLoss
    ) : Entity
    {
        $ladder = $this->mapper->create(array_merge(compact($this->filter->getFields()), [
            'organization' => $this->organization->getId()
        ]));

        if (!$this->mapper->isUniqueSlug($game, $ladder->getSlug())) {
            $this->filter->writeNameUnavailableMessage();
        }

        if (!$this->filter->hasErrors()) {
            $this->filter->writeSuccessMessage();
            $this->mapper->insert($ladder);

            return $ladder;
        }

        return $this->mapper->create();
    }
}
