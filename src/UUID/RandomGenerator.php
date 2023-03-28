<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Fork Of Simple UUID-V4 Generator By Kodus - https://github.com/kodus/uuid-v4
 *
 */

namespace System\UUID;

use Contracts\UUID\RandomGenerator as Contract;

class RandomGenerator implements Contract
{

    private const UUID_FORMAT = '%02x%02x%02x%02x-%02x%02x-%02x%02x-%02x%02x-%02x%02x%02x%02x%02x%02x';

    private const UUID_V4_PATTERN = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';


    public function generate() : string
    {
        $bytes = unpack('C*', random_bytes(16));

        return sprintf(self::UUID_FORMAT,
            $bytes[1], $bytes[2], $bytes[3], $bytes[4],
            $bytes[5], $bytes[6],
            $bytes[7] & 0x0f | 0x40, $bytes[8],
            $bytes[9] & 0x3f | 0x80, $bytes[10],
            $bytes[11], $bytes[12], $bytes[13], $bytes[14], $bytes[15], $bytes[16]
        );
    }


    public function isValid(string $uuid) : bool
    {
        return preg_match(self::UUID_V4_PATTERN, $uuid) === 1;
    }
}
