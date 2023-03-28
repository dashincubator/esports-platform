<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Decrypt/Encrypt Text
 *
 */

namespace Contracts\Encryption;

interface Encrypter
{

    /**
     *  Decrypt Encrypted Message
     *
     *  @param string $payload Encrypted Message To Decrypt
     *  @return string|null null If Invalid Payload, Decrypted Message If Successful
     *  @throws Exception If Message Was Altered Or Tampered With
     */
    public function decrypt(string $payload) : ?string;


    /**
     *  Encrypt Message
     *
     *  @param string $message Message To Encrypt
     *  @return string Encrypted Message
     */
    public function encrypt(string $message) : string;


    /**
     *  Generate Encryption Key
     *
     *  @return string
     */
    public function generateKey() : string;
}
