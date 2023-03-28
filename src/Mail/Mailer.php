<?php declare(strict_types=1);

namespace System\Mail;

use Contracts\Mail\{Mail, Mailer as Contract};
use Mail as PEARMail;
Use PEAR;

class Mailer implements Contract
{

    private $host;

    private $persist;

    private $port;

    private $smtp;


    public function __construct(string $host, bool $persist, int $port, ?string $password = null, ?string $username = null)
    {
        $this->host = $host;
        $this->password = $password;
        $this->persist = $persist;
        $this->port = $port;
        $this->username = $username;
    }


    private function getSMTP()
    {
        if (is_null($this->smtp)) {
            $config = [
                'host' => $this->host,
                'persist' => $this->persist,
                'port' => $this->port
            ];
            
            if ($this->password && $this->username) {
                $config['password'] = $this->password;
                $config['username'] = $this->username;
            }
            
            $this->smtp = PEARMail::factory('smtp', $config);
        }

        return $this->smtp;
    }


    public function send(Mail $mail) : bool
    {
        return !PEAR::isError(
            $this->getSMTP()->send(
                $mail->getTo(),
                $mail->getHeaders(),
                $mail->getBody()
            )
        );
    }
}
