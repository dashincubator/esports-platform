<?php declare(strict_types=1);

namespace Contracts\Time;

interface Time
{

    /**
     *  Builds Associative Array Of Timezone Identifiers Paired With Current Time
     *  In Timezone.
     *
     *  Example: ['timezone identifier' => '2:56am']
     */
    public function generateIdentifierListWithTime() : array;


    /**
     *  @return int Unix Timestamp
     */
    public function now() : int;


    /**
     *  Set User Timezone To Convert All Application Timestamps To User's Timezone
     *
     *  @param string $identifier Timezone Identifier
     *  @throws Exception If Identifier Is Invalid
     */
    public function setTimezone(string $identifier) : void;
}
