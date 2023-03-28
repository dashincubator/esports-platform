<?php

namespace App\Commands\User\Membership\Earned;

use App\Commands\AbstractFilter;
use Contracts\Configuration\Configuration;
use Contracts\Validation\{MessageTemplates, Validator};

class Filter extends AbstractFilter
{

    private $memberships;


    public function __construct(Configuration $config, MessageTemplates $templates, Validator $validator)
    {
        parent::__construct($templates, $validator);

        $this->memberships = implode(',', array_keys($config->get('membership.payout.options')));
    }


    protected function getRules(array $data = []) : array
    {
        return [
            'days' => [
                "in:{$this->memberships}" => $this->templates->invalid('membership days'),
                'int' => $this->templates->invalid('membership days'),
                'required' => $this->templates->required('membership days')
            ],
            'users' => [
                'array' => $this->templates->invalid('user'),
                'required' => $this->templates->required('user')
            ],
            'users.*' => [
                'int' => $this->templates->invalid('users list'),
                'required' => $this->templates->required('users list')
            ]
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Memberships have been applied to each account successfully!';
    }
}
