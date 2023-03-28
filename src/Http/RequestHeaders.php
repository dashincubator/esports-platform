<?php declare(strict_types=1);

namespace System\Http;

use Contracts\Http\RequestHeaders as Contract;
use Contracts\Collections\Associative as Collection;

class RequestHeaders extends AbstractHeaders implements Contract
{

    // List Of HTTP Request Headers That Don't Begin With "HTTP_"
    private const SPECIAL = [
        'AUTH_TYPE',
        'CONTENT_LENGTH',
        'CONTENT_TYPE',
        'PHP_AUTH_DIGEST',
        'PHP_AUTH_PW',
        'PHP_AUTH_TYPE',
        'PHP_AUTH_USER'
    ];


    public function __construct(Collection $headers, array $values = [])
    {
        parent::__construct($headers);

        // Only Add "HTTP_" Server Values Or "$special" Values
        foreach ($values as $name => $value) {
            $name = strtoupper($name);

            if (in_array($name, self::SPECIAL) || mb_strpos($name, 'HTTP_') === 0) {
                $this->set($name, $value);
            }
        }
    }


    protected function normalize(string $name) : string
    {
        $name = parent::normalize($name);

        if (mb_strpos($name, 'http-') === 0) {
            $name = mb_substr($name, 5);
        }

        return $name;
    }
}
