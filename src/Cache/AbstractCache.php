<?php declare(strict_types=1);

namespace System\Cache;

use Contracts\Encryption\Encrypter;
use Exception;

abstract class AbstractCache
{

    // Encryption Class Used On Data
    private $encrypter;

    // Prefix Used On All Keys
    private $prefix = '';

    // Whether Or Not To Use The Encrypter Provided
    private $useEncryption = false; 


    protected function prefix($key) : string
    {
        if ($this->prefix) {
            $key = "{$this->prefix}:{$key}";
        }

        return (string) $key;
    }


    protected function serialize($data)
    {
        $data = serialize($data);

        if ($this->useEncryption) {
            if (is_null($this->encrypter)) {
                throw new Exception('Encrypter Was Not Set In Cache Adapter');
            }

            $data = $this->encrypter->encrypt($data);
        }

        return gzencode($data, 9);
    }


    public function setEncrypter(Encrypter $encrypter) : void
    {
        $this->encrypter = $encrypter;
    }


    public function setPrefix($prefix) : void
    {
        $this->prefix = $prefix;
    }


    public function useEncryption(bool $use) : void
    {
        $this->useEncryption = $use;
    }


    protected function unserialize($data)
    {
        if (is_null($data) || $data === false) {
            return $data;
        }

        if ($this->useEncryption) {
            if (is_null($this->encrypter)) {
                throw new Exception('Encrypter Was Not Set In Cache Adapter');
            }

            $data = $this->encrypter->decrypt($data);
        }

        return unserialize(gzdecode($data));
    }
}
