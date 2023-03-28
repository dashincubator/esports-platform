<?php declare(strict_types=1);

namespace System\Mail;

use Contracts\Mail\Mail as Contract;

class Mail implements Contract
{

    private $body;

    private $from;

    private $subject;

    private $to;


    public function body(string $body) : void
    {
        $this->body = $body;
    }


    public function from(string $email, string $name) : void
    {
        $this->from = "{$name} <{$email}>";
    }


    public function getBody() : string
    {
        return $this->body;
    }


    public function getHeaders() : array
    {
        return [
            'From' => $this->from,
            'To' => $this->to,
            'Subject' => $this->subject
        ];
    }


    public function getTo() : string
    {
        return $this->to;
    }


    public function subject(string $subject) : void
    {
        $this->subject = $subject;
    }


    public function to(string $email, string $name) : void
    {
        $this->to = "{$name} <{$email}>";
    }
}
