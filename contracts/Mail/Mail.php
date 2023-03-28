<?php declare(strict_types=1);

namespace Contracts\Mail;

interface Mail
{

    /**
     *  @param string $body Email Text
     */
    public function body(string $body) : void;


    /**
     *  @param string $email
     *  @param string $name
     */
    public function from(string $email, string $name) : void;


    /**
     *  @return string Email Text
     */
    public function getBody() : string;


    /**
     *  @return array
     */
    public function getHeaders() : array;


    /**
     *  @return string
     */
    public function getTo() : string;


    /**
     *  @param string $subject
     */
    public function subject(string $subject) : void;


    /**
     *  @param string $email
     *  @param string $name
     */
    public function to(string $email, string $name) : void;
}
