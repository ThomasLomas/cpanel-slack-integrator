<?php

use Symfony\Component\HttpFoundation\Response;

class AccountController extends AbstractController
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

        if(count($arguments) !== 5) {
            return new Response('Please specify domain, username, password and plan');
        }

        list($command, $domain, $username, $password, $plan) = $arguments;

        $create = $this->xmlapi->createacct(array(
            'domain' => $domain,
            'username' => $username,
            'password' => $password,
            'plan' => $plan
        ));

        return new Response((string)$create->result->statusmsg);
    }
}
