<?php

namespace App\View\Extensions;

use ArrayAccess;
use Contracts\View\Extensions\Data;

class Avg
{

    public function wins(Data $data) : int
    {
        if ($data['losses'] + $data['wins'] === 0) {
            return 0;
        }

        return number_format((($data['wins'] ?? 0) / ($data['losses'] + $data['wins']) * 100)) + 0;
    }
}
