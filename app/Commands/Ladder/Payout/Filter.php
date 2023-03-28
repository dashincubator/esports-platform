<?php

namespace App\Commands\Ladder\Payout;

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
            'id' => [
                'int' => $this->templates->invalid('ladder'),
                'required' => $this->templates->required('ladder')
            ],
            'payout' => [
                'array' => $this->templates->invalid('payout list'),
                'required' => $this->templates->required('payout list')
            ],
            'payout.*.amount' => [
                'int' => $this->templates->invalid('payout list amount'),
                'required' => $this->templates->required('payout list amount')
            ],
            'payout.*.membership' => [
                "in:{$this->memberships}" => $this->templates->invalid('payout list membership'),
                'int' => $this->templates->invalid('payout list membership'),
                'required' => $this->templates->required('payout list membership')
            ],
            'payout.*.place' => [
                'int' => $this->templates->invalid('payout list place'),
                'min:1' => $this->templates->min('payout list place', 0, 1),
                'required' => $this->templates->required('payout list place')
            ],
            'payout.*.team' => [
                'int' => $this->templates->invalid('payout list team'),
                'required' => $this->templates->required('payout list team')
            ],
        ];
    }


    protected function getSuccessMessage() : string
    {
        return 'Payout processed successfully, ladder is now closed';
    }


    public function writeDuplicateTeamsFoundMessage() : void
    {
        $this->error('2 or more payouts use the same team id, fix this and try again');
    }


    public function writeInvalidTeamMessage(int ...$id) : void
    {
        $this->error(implode(', ', $id) . ' team id(s) are not associated with this ladder');
    }
}
