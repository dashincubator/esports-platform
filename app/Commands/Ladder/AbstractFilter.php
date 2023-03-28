<?php

namespace App\Commands\Ladder;

use App\Commands\AbstractFilter as AbstractParent;
use Contracts\Validation\{MessageTemplates, Validator};
use App\Services\Api\Managers;

abstract class AbstractFilter extends AbstractParent
{

    private $formats;


    public function __construct(Managers $managers, MessageTemplates $templates, Validator $validator)
    {
        parent::__construct($templates, $validator);

        $this->formats = implode(',', array_merge($managers->getCompetitionFormatOptions(), ['']));
    }


    protected function getRules(array $data = []) : array
    {
        return [
            'endsAt' => [
                'required' => $this->templates->required('ends at'),
                'timestamp' => $this->templates->invalid('ends at')
            ],
            'entryFee' => [
                'numeric' => $this->templates->invalid('entry fee'),
                'required' => $this->templates->required('entry fee')
            ],
            'entryFeePrizes' => [
                'array' => $this->templates->invalid('entry fee prizes list')
            ],
            'entryFeePrizes.*.key' => [],
            'entryFeePrizes.*.value' => [
                'max:1' => $this->templates->max('entry fee prizes list value', 0, 1),
                'min:0.01' => $this->templates->min('entry fee prizes list value', 0, 0.1),
                'numeric' => $this->templates->invalid('entry fee prizes list value')
            ],
            'firstToScore' => [
                'int' => $this->templates->invalid('first to score')
            ],
            'format' => [
                "in:{$this->formats}" => 'game api Key is invalid',
                'string' => $this->templates->invalid('game stats key')
            ],
            'gametypes' => [
                'array' => $this->templates->invalid('gametypes list')
            ],
            'gametypes.*' => [
                'int' => $this->templates->invalid('gametypes list')
            ],
            'maxPlayersPerTeam' => [
                'int' => $this->templates->invalid('maximum players per team'),
                'required' => $this->templates->required('maximum players per team')
            ],
            'membershipRequired' => [
                'bool' => $this->templates->invalid('membership switch')
            ],
            'minPlayersPerTeam' => [
                'int' => $this->templates->invalid('minimum players per team'),
                'required' => $this->templates->required('minimum players per team')
            ],
            'name' => [
                'required' => $this->templates->required('name'),
                'string' => $this->templates->string('name')
            ],
            'prizePool' => [
                'numeric' => $this->templates->invalid('prize pool')
            ],
            'prizes' => [
                'array' => $this->templates->invalid('prizes list')
            ],
            'prizes.*.key' => [],
            'prizes.*.values' => [
                'array' => $this->templates->invalid('prizes list values')
            ],
            'prizes.*.values.*' => [
                'string' => $this->templates->string('prizes list values')
            ],
            'rules' => [
                'array' => $this->templates->invalid('rules list')
            ],
            'rules.*.key' => [],
            'rules.*.values' => [
                'array' => $this->templates->invalid('rules list values')
            ],
            'rules.*.values.*' => [
                'string' => $this->templates->string('rules list values')
            ],
            'prizesAdjusted' => [
                'bool' => $this->templates->invalid('prizes adjusted switch')
            ],
            'slug' => [
                'string' => $this->templates->string('slug')
            ],
            'startsAt' => [
                'required' => $this->templates->required('starts at'),
                'timestamp' => $this->templates->invalid('starts at')
            ],
            'stopLoss' => [
                'int' => $this->templates->invalid('stop loss')
            ]
        ];
    }


    protected function getSuccessMessage(string $action = '') : string
    {
        return "Ladder {$action} successfully!";
    }


    public function writeInvalidGameApiKeyMessage() : void
    {
        $this->error('Game API Key is invalid');
    }


    public function writeNameUnavailableMessage() : void
    {
        $this->error('Ladder name is already in use');
    }
}
