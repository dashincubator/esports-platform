<?php

namespace App\Commands\Game;

use App\Commands\AbstractFilter as AbstractParent;
use Contracts\Configuration\Configuration;
use Contracts\Validation\{MessageTemplates, Validator};

abstract class AbstractFilter extends AbstractParent
{

    private $whitelist;


    public function __construct(Configuration $config, MessageTemplates $templates, Validator $validator)
    {
        parent::__construct($templates, $validator);
        $this->whitelist = implode(',', array_merge(array_keys($config->get('game.accounts')), ['']));
    }


    protected function getRules(array $data = []) : array
    {
        return [
            'account' => [
                "in:{$this->whitelist}" => $this->templates->string('game account key'),
                'string' => $this->templates->string('game account key')
            ],
            'name' => [
                'required' => $this->templates->required('name'),
                'string' => $this->templates->string('name')
            ],
            'slug' => [
                'string' => $this->templates->string('slug')
            ],
            'view' => [
                'string' => $this->templates->string('view key')
            ]
        ];
    }


    public function writeGameAccountRequiredMessage() : void
    {
        $this->error('Game account cannot be left empty');
    }


    public function writeNameUnavailableMessage() : void
    {
        $this->error('Game name is already in use');
    }
}
