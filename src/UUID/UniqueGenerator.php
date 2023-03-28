<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Fork Of Simple UUID-V5 Generator By Kodus - https://github.com/kodus/uuid-v5
 *
 */

namespace System\UUID;

use Contracts\UUID\UniqueGenerator as Contract;
use Exception;

class UniqueGenerator implements Contract
{

    private const UUID_FORMAT = '%02x%02x%02x%02x-%02x%02x-%02x%02x-%02x%02x-%02x%02x%02x%02x%02x%02x';

    private const UUID_V5_NS_DNS = "6ba7b810-9dad-11d1-80b4-00c04fd430c8";

    private const UUID_V5_NS_OID = "6ba7b812-9dad-11d1-80b4-00c04fd430c8";

    private const UUID_V5_NS_URL = "6ba7b811-9dad-11d1-80b4-00c04fd430c8";

    private const UUID_V5_NS_X500 = "6ba7b814-9dad-11d1-80b4-00c04fd430c8";

    private const UUID_V5_PATTERN = '/^[0-9A-F]{8}-[0-9A-F]{4}-5[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';


    private function generate(string $namespace, string $name) : string
    {
        $namespace = @pack('H*', str_replace('-', '', $namespace));

        if (mb_strlen($namespace) !== 16) {
            throw new Exception('Invalid Namespace UUID');
        }

        $bytes = unpack('C*', sha1($namespace . $name, true));

        return sprintf(self::UUID_FORMAT,
            $bytes[1], $bytes[2], $bytes[3], $bytes[4],
            $bytes[5], $bytes[6],
            $bytes[7] & 0x0f | 0x50, $bytes[8],
            $bytes[9] & 0x3f | 0x80, $bytes[10],
            $bytes[11], $bytes[12], $bytes[13], $bytes[14], $bytes[15], $bytes[16]
        );
    }


    public function generateDNS(string $name) : string
    {
        return $this->generate(self::UUID_V5_NS_DNS, $name);
    }


    public function generateOID(string $name) : string
    {
        return $this->generate(self::UUID_V5_NS_OID, $name);
    }


    public function generateURL(string $name) : string
    {
        return $this->generate(self::UUID_V5_NS_URL, $name);
    }


    public function generateX500(string $name) : string
    {
        return $this->generate(self::UUID_V5_NS_X500, $name);
    }


    public function isValid(string $uuid) : bool
    {
        return preg_match(self::UUID_V5_PATTERN, $uuid) === 1;
    }
}
