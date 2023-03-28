<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Slug Generator
 *
 */

namespace Contracts\Slug;

interface Generator
{

    /**
     *  Adds Find/Replace Rule During Slug Generation
     *
     *  @param string $key Key To Look For
     *  @param string $value Value To Replace With
     */
    public function addRule(string $key, string $value) : void;


    /**
     *  Load Ruleset Through Slug Provider
     *
     *  @param string $key Passed To Slug Rule Provider
     *  @throws Exception If Ruleset Does Not Exist
     */
    public function addRuleset(string $key) : void;


    /**
     *  Generate Slug From String
     *
     *  @param string $generating String To Convert To Slug
     *  @return string Slugified String
     */
    public function generate(string $generating) : string;
}
