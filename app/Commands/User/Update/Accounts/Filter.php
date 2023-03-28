<?php

namespace App\Commands\User\Update\Accounts;

use App\Commands\AbstractFilter;
use Contracts\Configuration\Configuration;
use Contracts\Validation\{MessageTemplates, Validator};

class Filter extends AbstractFilter
{

    private $accounts;


    public function __construct(Configuration $config, MessageTemplates $templates, Validator $validator)
    {
        parent::__construct($templates, $validator);

        $this->accounts = array_merge($config->get('social.accounts'), $config->get('game.accounts'));
    }


    protected function getRules(array $data = []) : array
    {
        $rules = [
            'accounts' => [
                'array' => $this->templates->invalid('accounts'),
                'required' => $this->templates->required('accounts')
            ],
            'id' => [
                'int' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ]
        ];

        foreach ($this->accounts as $key => $name) {
            $rules["accounts.{$key}"] = [
                'string' => $this->templates->string($name)
            ];
        }

        return $rules;
    }


    protected function getSuccessMessage() : string
    {
        return 'Account updated successfully!';
    }


    public function writeAccountAlreadyInUseMessage(string $key) : void
    {
        $this->error("{$this->accounts[$key]} is already in use by another user. If you own this account submit a support ticket to verify ownership.");
    }
}
