<?php declare(strict_types=1);

namespace System\MultiCurl\Parser;

use Contracts\MultiCurl\Parser\Json as Contract;

class Json implements Contract
{

    public function toJson(string $content) : string
    {
        $json = json_decode($content, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $content;
        }

        return json_encode([
            'error' => 'Invalid Json Provided To MultiCurl Json Parser'
        ]);
    }
}
