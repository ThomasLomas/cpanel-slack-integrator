<?php

use Symfony\Component\HttpFoundation\Response;

class EmailController extends AbstractController
{
    public function getResponse()
    {
        $arguments = $this->getArguments();

        if(count($arguments) <= 1) {
            return new Response('Please specify command');
        }

        switch(strtolower($arguments[0])) {
            case 'create':
                return $this->create();
                break;
        }

        return new Response('Command not found');
    }

    protected function create()
    {
        $arguments = $this->getArguments();

        if(count($arguments) !== 4) {
            return new Response('Please specify email, password and quota');
        }

        list($command, $email, $password, $quota) = $arguments;

        $quota = (int)$quota;

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response('Invalid email');
        }

        if($quota < 0) {
            return new Response('Quota cannot be negative');
        }

        list($user, $domain) = explode('@', $email);

        $userData = $this->xmlapi->domainuserdata($domain);

        if($userData->result->status == 0) {
            return new Reponse($userData->result->statusmsg);
        }

        $accountUsername = (string) $userData->userdata->user;

        $this->xmlapi->addpop($accountUsername, array($user, $password, $quota, $domain));

        return new Response(
            sprintf(
                '%s has been created',
                $email
            )
        );
    }
}
