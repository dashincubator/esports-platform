<?php declare(strict_types=1);

namespace System\Configuration;

use Contracts\Configuration\Configuration as Contract;
use System\Collections\Associative as Collection;

class Configuration extends Collection implements Contract
{

    protected $message = "'%s' Could not Be Found In Configuration";
}
