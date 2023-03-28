<?php declare(strict_types=1);

namespace System\MultiCurl\Parser;

use Contracts\MultiCurl\Parser\Xml as Contract;

class Xml implements Contract
{

    public function __construct()
    {
        libxml_use_internal_errors(true);
    }


    public function toJson(string $content) : string
    {
        $xml = simplexml_load_string($content);

        if ($xml && is_object($xml)) {
            return json_encode($this->toJsonLoop($xml));
        }

        return $content;
    }


    private function toJsonLoop($xml)
    {
        $out = [];

        if (count((array) $xml) === 0) {
            return (string) $xml;
        }

        foreach ((array) $xml as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $value = $this->toJsonLoop($value);
            }

            $out[$key] = $value;
        }

        if (is_object($xml)) {
            $content = (string) $xml->children('content', true)[0];

            if (mb_strlen($content) > 0) {
                $out['content'] = $content;
            }
        }

        return $out;
    }
}
