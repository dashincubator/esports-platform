<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Fork Of Slugify By Cocur - https://github.com/cocur/slugify
 *
 */

namespace System\Slug;

use Contracts\Collections\Associative as Collection;
use Contracts\Slug\Generator as Contract;

class Generator implements Contract
{

    // Languages Are Ordered By Website Count Of Each Language
    // - https://en.wikipedia.org/wiki/Languages_used_on_the_Internet#Content_languages_for_websites
    private const DEFAULT_RULESETS = [
        'default',
        'azerbaijani',
        'burmese',
        'hindi',
        'georgian',
        'norwegian',
        'vietnamese',
        'ukrainian',
        'latvian',
        'finnish',
        'greek',
        'czech',
        'arabic',
        'turkish',
        'polish',
        'german',
        'russian',
        'romanian'
    ];


    // Collection Of Inactive Rules
    private $collection;

    // Convert To Lowercase
    private $lowercase;

    // Default Regex Option
    private $regex;

    // Active Rules Used By Generator
    private $rules;

    // Delimiter
    private $separator;


    public function __construct(
        Collection $collection,
        bool $lowercase = true,
        string $regex = '/[^A-Za-z0-9]+/',
        array $rulesets = [],
        string $separator = '-'
    ) {
        $this->collection = $collection;
        $this->lowercase = $lowercase;
        $this->regex = $regex;
        $this->separator = $separator;

        foreach (array_merge(self::DEFAULT_RULESETS, $rulesets) as $ruleset) {
            $this->addRuleset($ruleset);
        }
    }


    public function addRule(string $key, string $value) : void
    {
        $this->rules[$key] = $value;
    }


    public function addRuleset(string $key) : void
    {
        foreach ($this->collection->get($key) as $key => $value) {
            $this->addRule($key, $value);
        }
    }


    public function generate(string $generating) : string
    {
        foreach (['striptags', 'replace', 'lowercase', 'trim'] as $method) {
            $generating = $this->{$method}($generating);
        }

        return $generating;
    }


    private function lowercase(string $string) : string
    {
        if ($this->lowercase) {
            $string = mb_strtolower($string);
        }

        return $string;
    }


    private function replace(string $string) : string
    {
        $string = strtr($string, $this->rules);
        $string = preg_replace($this->regex, $this->separator, $string);

        return $string;
    }


    private function striptags(string $string) : string
    {
        return strip_tags($string);
    }


    private function trim(string $string) : string
    {
        return trim($string, $this->separator);
    }
}
