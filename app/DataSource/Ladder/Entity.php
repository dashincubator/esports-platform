<?php

namespace App\DataSource\Ladder;

use App\DataSource\AbstractEntity;
use Contracts\Slug\Generator;

class Entity extends AbstractEntity
{

    // Cleanup/Rename
    private const GRACE_PERIOD = 60;


    private const TYPE_CHALLENGE = 'challenge';

    private const TYPE_LADDER = 'ladder';

    private const TYPE_LEAGUE = 'league';


    protected $guarded = ['id', 'type'];

    private $slug;


    public function __construct(Generator $slug, Record $record)
    {
        parent::__construct($record);

        $this->slug = $slug;
    }


    public function close() : void
    {
        $this->set('endsAt', time());
    }


    public function fill(array $values) : void
    {
        parent::fill($values);
        $this->updateType();
    }


    public function getApiGracePeriod() : int
    {
        return $this->getEndsAt() + (self::GRACE_PERIOD * 60);
    }


    public function inserting() : void
    {
        $this->updateType();
    }


    public function isApiGracePeriodOpen() : bool
    {
        return $this->isClosed() && !$this->getFirstToScore() && !$this->isOpen() && (time() < $this->getApiGracePeriod());
    }


    public function isClosed() : bool
    {
        if ($this->get('endsAt') < time()) {
            return true;
        }

        return false;
    }


    public function isLadder() : bool
    {
        return $this->get('type') === self::TYPE_LADDER;
    }


    public function isLeague() : bool
    {
        return $this->get('type') === self::TYPE_LEAGUE;
    }


    public function isMatchFinderRequired() : bool
    {
        return !$this->getFormat();
    }


    public function isMembershipRequired() : bool
    {
        return $this->get('membershipRequired');
    }


    public function isOpen() : bool
    {
        return ($this->get('startsAt') < time()) && !$this->isClosed();
    }


    public function isRegistrationClosed() : bool
    {
        if ($this->get('endsAt') < time()) {
            return true;
        }

        return false;
    }


    public function isTeamLockRequired() : bool
    {
        return ($this->get('type') === self::TYPE_LEAGUE)
            || $this->isMembershipRequired()
            || $this->get('stopLoss') > 0
            || $this->get('entryFee') > 0;
    }


    protected function setEntryFeePrizes(array $input) : array
    {
        $output = [];

        foreach ($input as $prizes) {
            $output[$prizes['key']] = $prizes['value'];
        }

        return $output;
    }


    protected function setName(string $name) : string
    {
        $this->set('slug', $name);

        return $name;
    }


    protected function setPrizes(array $input) : array
    {
        $output = [];

        foreach ($input as $prizes) {
            $output[$prizes['key']] = $prizes['values'] ?? [];
        }

        return $output;
    }


    protected function setRules(array $input) : array
    {
        $output = [];

        foreach ($input as $prizes) {
            $output[] = [
                'title' => $prizes['key'],
                'content' => ($prizes['values'] ?? [])
            ];
        }

        return $output;
    }


    protected function setSlug(string $slugify) : string
    {
        if ($slugify === '') {
            $slugify = $this->get('name');
        }

        return $this->slug->generate($slugify);
    }


    public function started() : bool
    {
        if ($this->get('startsAt') < time()) {
            return true;
        }

        return false;
    }


    public function toArray() : array
    {
        $data = parent::toArray();
        $data['apiGracePeriod'] = $this->getApiGracePeriod();
        $data['isApiGracePeriodOpen'] = $this->isApiGracePeriodOpen();
        $data['isClosed'] = $this->isClosed();
        $data['isOpen'] = $this->isOpen();
        $data['isMatchFinderRequired'] = $this->isMatchFinderRequired();
        $data['isMembershipRequired'] = $this->isMembershipRequired();
        $data['isRegistrationOpen'] = !$this->isRegistrationClosed();
        $data['isTeamLockRequired'] = $this->isTeamLockRequired();

        $data['isLadder'] = $this->isLadder();
        $data['isLeague'] = $this->isLeague();

        return $data;
    }


    private function updateType() : void
    {
        $type = self::TYPE_LADDER;

        if (
            $this->get('entryFee')
            || $this->get('format')
            || $this->get('stopLoss')
            || $this->get('firstToScore')
            || $this->get('membershipRequired')
        ) {
            $type = self::TYPE_LEAGUE;
        }

        $this->set('type', $type);
    }


    public function updating() : void
    {
        $this->updateType();
    }


    public function useDefaultMatchfinder() : bool
    {
        return $this->get('format') === '';
    }
}
