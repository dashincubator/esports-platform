<?php declare(strict_types=1);

namespace System\Hashing;

use Contracts\Hashing\Hasher as Contract;
use Exception;

class BcryptHasher implements Contract
{

    // Hashing Algorithm Used By 'password_hash'
    private $algorithm = PASSWORD_BCRYPT;

    // Default Hashing Options
    private $options = [
        'cost' => 10
    ];


    public function hash(string $value, array $options = []) : string
    {
        $hash = password_hash($value, $this->algorithm, array_merge($this->options, $options));

        if ($hash === false) {
            throw new Exception("Hasher Failed To Generate Hash For Algorithm {$this->algorithm}");
        }

        return $hash;
    }


    public function needsRehash(string $hash, array $options = []) : bool
    {
        return password_needs_rehash($hash, $this->algorithm, array_merge($this->options, $options));
    }


    public function verify(string $unhashed, string $hash) : bool
    {
        return password_verify($unhashed, $hash);
    }
}
