<?php

namespace App\Commands\Ladder\Update;

use App\Commands\AbstractCommand;
use App\Commands\Ladder\Upload\{Banner\Command as BannerCommand, Card\Command as CardCommand};
use App\Commands\Ladder\Payout\Command as PayoutCommand;
use App\DataSource\Ladder\{Entity, Mapper};
use Contracts\Upload\File;

class Command extends AbstractCommand
{

    private $command;

    private $mapper;


    public function __construct(BannerCommand $banner, CardCommand $card, Filter $filter, Mapper $mapper, PayoutCommand $payout)
    {
        $this->command = compact('banner', 'card', 'payout');
        $this->filter = $filter;
        $this->mapper = $mapper;
    }


    protected function run(
        ?File $banner,
        ?File $card,
        int $endsAt,
        float $entryFee,
        ?array $entryFeePrizes,
        ?int $firstToScore,
        ?string $format,
        array $gametypes,
        int $id,
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
    ) : bool
    {
        $ladder = $this->mapper->findById($id);

        if ($ladder->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        else {
            if ($name !== $ladder->getName() || (is_string($slug) && $slug !== $ladder->getSlug())) {
                $ladder->fill(compact('name', 'slug'));

                if (!$this->mapper->isUniqueSlug($ladder->getGame(), $ladder->getSlug())) {
                    $this->filter->writeNameUnavailableMessage();
                }
            }
        }

        if (!$this->filter->hasErrors()) {
            foreach (['banner', 'card'] as $key) {
                if (!${$key}) {
                    continue;
                }

                ${$key} = $this->delegate($this->command[$key], [
                    'default' => $ladder->{'get' . ucfirst($key)}(),
                    'file' => ${$key},
                    'name' => $id
                ]);
            }
        }

        if (!$this->filter->hasErrors()) {
            $ladder->fill(compact($this->filter->getFields(['id'])));

            $this->filter->writeSuccessMessage();
            $this->mapper->update($ladder);
        }

        return $this->booleanResult();
    }
}
