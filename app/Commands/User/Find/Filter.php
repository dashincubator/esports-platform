<?php

namespace App\Commands\User\Find;

use App\Commands\AbstractFilter;
use Contracts\Configuration\Configuration;
use Contracts\Validation\{MessageTemplates, Validator};

class Filter extends AbstractFilter
{

    private $whitelist;


    public function __construct(Configuration $config, MessageTemplates $templates, Validator $validator)
    {
        parent::__construct($templates, $validator);
        $this->whitelist = implode(',', array_merge(array_keys($config->get('game.accounts')), ['id']));
    }


    protected function getRules(array $data = []) : array
    {
        return [
            'column' => [
                "in:{$this->whitelist}" => 'User could not be found',
                'required' => $this->templates->required('find by key'),
                'string' => $this->templates->string('find by key')
            ],
            'value' => [
                'required' => $this->templates->required('find by value')
            ]
        ];
    }


    public function writeUserNotFoundMessage() : void
    {
        $this->error('User could not be found');
    }
}
