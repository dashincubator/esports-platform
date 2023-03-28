<?php

namespace App\Commands\User\ForgotPassword\Send;

use App\Commands\AbstractCommand;
use App\DataSource\User\Mapper as UserMapper;
use App\DataSource\User\ForgotPassword\Mapper as ForgotPasswordMapper;
use Contracts\Mail\{Factory, Mailer};
use Contracts\Routing\Router;

class Command extends AbstractCommand
{

    private $factory;

    private $email;

    private $mailer;

    private $mapper;

    private $router;


    public function __construct(
        Factory $factory,
        Filter $filter,
        ForgotPasswordMapper $fpw,
        Mailer $mailer,
        Router $router,
        UserMapper $user,
        string $body,
        string $email,
        string $name,
        string $subject
    ) {
        $this->factory = $factory;
        $this->filter = $filter;
        $this->email = [
            'body' => $body,
            'from' => [
                'email' => $email,
                'name' => $name
            ],
            'subject' => $subject
        ];
        $this->mailer = $mailer;
        $this->mapper = compact('fpw', 'user');
        $this->router = $router;
    }


    protected function run(int $id) : bool
    {
        $fpw = $this->mapper['fpw']->findById($id);
        $user = $this->mapper['user']->findById($fpw->getUser() ?? 0);

        if ($fpw->isEmpty() || $user->isEmpty()) {
            $this->filter->writeUnknownErrorMessage();
        }
        elseif ($fpw->alreadySent()) {
            $this->filter->writeAlreadyEmailedMessage();
        }

        if (!$this->filter->hasErrors()) {
            $url = $this->router->url('account.auth.reset-password', [
                'code' => $fpw->getCode(),
                'id' => $fpw->getId()
            ]);

            $mail = $this->factory->createMail();
            $mail->body(sprintf($this->email['body'], $this->email['from']['name']));
            $mail->from($this->email['from']['email'], $this->email['from']['name']);
            $mail->to($user->getEmail(), $user->getUsername());
            $mail->subject(sprintf($this->email['subject'], $this->email['from']['name']));

            if ($this->mailer->send($mail)) {
                $fpw->sent();
                $this->mapper['fpw']->update($fpw);
            }
            else {
                $this->filter->writeUnknownErrorMessage();
            }
        }

        return $this->booleanResult();
    }
}
