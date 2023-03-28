<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Fork Of FastRoute Version 2.0 By Nikic - https://github.com/nikic/FastRoute
 *
 *  Parses Route Strings Of The Following Form:
 *  "/user/{name}[/{id:[0-9]+}]"
 *
 */

namespace System\Routing;

use Contracts\Routing\RouteParser as Contract;
use Exception;

class RouteParser implements Contract
{

    // FastRoute Variable Regex
    private const VARIABLE_REGEX = "
        \{
            \s* ([a-zA-Z_][a-zA-Z0-9_-]*) \s*
            (?:
                : \s* ([^{}]*(?:\{(?-1)\}[^{}]*)*)
            )?
        \}
    ";


    // Replace Placeholder Values With Regex When Key Is Present
    private $rules;


    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }


    public function parse(string $pattern) : array
    {
        $patternWithoutClosingOptionals = rtrim($pattern, ']');
        $optionalsCount = mb_strlen($pattern) - mb_strlen($patternWithoutClosingOptionals);

        // Split On [ While Skipping Variables
        $segments = preg_split('~' . self::VARIABLE_REGEX . '(*SKIP)(*F) | \[~x', $patternWithoutClosingOptionals);

        if ($optionalsCount !== count($segments) - 1) {
            // If There Are Any ] In The Middle Of The Route, Throw A More Detailed Error Message
            if (preg_match('~' . self::VARIABLE_REGEX . '(*SKIP)(*F) | \]~x', $patternWithoutClosingOptionals)) {
                throw new Exception("Optional Segments Can Only Occur At The End Of A Route '{$pattern}'");
            }

            throw new Exception("Number Of Opening '[' And Closing ']' Does Not Match '{$pattern}'");
        }

        $current = '';
        $data = [];

        foreach ($segments as $index => $segment) {
            if ($segment === '' && $index !== 0) {
                throw new Exception("Empty Optional Route Segment");
            }

            $current .= $segment;
            $data[] = $this->parseVariables($current);
        }

        return $data;
    }


    private function parseVariables(string $pattern) : array
    {
        if (!preg_match_all(
            '~' . self::VARIABLE_REGEX . '~x', $pattern, $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        )) {
            return [$pattern];
        }

        $data = [];
        $offset = 0;

        foreach ($matches as $set) {
            $match = [
                 'string' => $set[0][0],
                 'offset' => $set[0][1]
            ];
            $placeholder = $set[1][0];
            $value = isset($set[2]) ? trim($set[2][0]) : '';

            if ($match['offset'] > $offset) {
                $data[] = mb_substr($pattern, $offset, $match['offset'] - $offset);
            }

            $data[] = [
                $placeholder,
                $this->rules[$value] ?? $value
            ];

            $offset = $match['offset'] + mb_strlen($match['string']);
        }

        if ($offset !== mb_strlen($pattern)) {
            $data[] = mb_substr($pattern, $offset);
        }

        return $data;
    }
}
