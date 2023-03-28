<?php declare(strict_types=1);

namespace System\Encryption;

use Contracts\Encryption\Encrypter as Contract;
use Exception;

class SodiumEncrypter implements Contract
{

    // 'sodium_pad'/'sodium_unpad' Length Parameter
    private const PADDING_LENGTH = 16;


    // Key To Use During Decryption/Encryption
    private $key;


    public function __construct(string $key)
    {
        $this->key = $key;
    }


    public function decrypt(string $payload) : ?string
    {
        $payload = base64_decode($payload);

        if ($payload === false) {
            return null;
        }

        if (!defined('CRYPTO_SECRETBOX_MACBYTES')) {
            define('CRYPTO_SECRETBOX_MACBYTES', 16);
        }

        if (mb_strlen($payload, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + CRYPTO_SECRETBOX_MACBYTES)) {
            throw new Exception('Message Was Altered');
        }

        $ciphertext = mb_substr($payload, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
        $nonce = mb_substr($payload, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');

        $message = sodium_crypto_secretbox_open($ciphertext, $nonce, $this->key);
        $message = sodium_unpad($message, $this->getPaddingLength());

        if ($message === false) {
            throw new Exception('Message Was Tampered With In Transit');
        }

        sodium_memzero($ciphertext);
        sodium_memzero($nonce);

        return $message;
    }


    public function encrypt(string $message) : string
    {
        $message = sodium_pad($message, $this->getPaddingLength());
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $ciphertext = sodium_crypto_secretbox($message, $nonce, $this->key);

        sodium_memzero($message);

        return base64_encode($nonce . $ciphertext);
    }


    public function generateKey() : string
    {
        return sodium_crypto_secretbox_keygen();
    }


    private function getPaddingLength() : int
    {
        if (self::PADDING_LENGTH <= 512) {
            return self::PADDING_LENGTH;
        }

        return 512;
    }
}
