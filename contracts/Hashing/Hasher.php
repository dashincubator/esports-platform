<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  One Way Hash Input Value
 *
 */

namespace Contracts\Hashing;

interface Hasher
{

    /**
     *  Generate Hash Of A Given Value
     *
     *  @param string $value Value To Hash
     *  @param array $options List Of Algorithm-Dependent Options
     *  @return string Hashed Value
     *  @throws Exception Thrown If Hashing Fails
     */
    public function hash(string $value, array $options = []) : string;


    /**
     *  Check If Hashed Value Was Hashed With The Input Options
     *
     *  @param string $hash Hashed Value To Check
     *  @param array $options List Of Algorithm-Dependent Options
     *  @return bool True If Hash Needs To Be Rehashed, Otherwise False
     */
    public function needsRehash(string $hash, array $options = []) : bool;


    /**
     *  Check If Hashed Value Matches Unhashed Value
     *
     *  @param string $unhashed Unhashed Value To Verify
     *  @param string $hash Hashed Value To Verify Against
     *  @return bool True If Values Match, Otherwise False
     */
    public function verify(string $unhashed, string $hash) : bool;
}
