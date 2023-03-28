<?php declare(strict_types=1);

/**
 *------------------------------------------------------------------------------
 *
 *  Http Response
 *
 */

namespace Contracts\Http;

use DateTime;

interface Response
{

    /**
     *  @return string
     */
    public function getContent() : string;


    /**
     *  @return ResponseHeaders
     */
    public function getHeaders() : ResponseHeaders;


    /**
     *  @return string HTTP Protocol Version
     */
    public function getProtocolVersion() : string;


    /**
     *  @return string HTTP Status Code
     */
    public function getStatusCode() : int;


    /**
     *  @return string HTTP Status Text
     */
    public function getStatusText() : string;


    /**
     *  Whether Or Not Headers Have Already Been Sent
     *
     *  @param bool True If Headers Have Already Been Sent, Otherwise False
     */
    public function headersAlreadySent() : bool;


    /**
     *  Sends Headers And Content
     */
    public function send() : void;


    /**
     *  @param mixed $content
     */
    public function setContent($content) : void;


    /**
     *  Set Expiration Time Of The Page
     *
     *  @param DateTime $expiration The Expiration Time
     */
    public function setExpiration(DateTime $expiration) : void;


    /**
     *  Set HTTP Protocol Version
     *
     *  @param string $version HTTP Protocol Version
     *  @throws Exception Thrown If Protocol Version Is Invalid
     */
    public function setProtocolVersion(string $version) : void;


    /**
     *  Set HTTP Status Code + Text
     *
     *  @param int $code HTTP Status Code
     *  @param string $text Http Status Text; If '' Status Text Is Set Using '$key'
     */
    public function setStatus(int $code, string $text = '') : void;
}
