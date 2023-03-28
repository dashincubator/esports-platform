<?php declare(strict_types=1);

namespace System\Validation\Rules;

class Timezone extends AbstractRule
{

    private $timezones;


    public function __construct()
    {
        $this->timezones = timezone_identifiers_list();
    }


    protected function validate($input) : bool
    {
        return in_array($input, $this->timezones);
    }
}
