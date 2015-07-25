<?php

use Symfony\Component\HttpFoundation\Response;

class HelpController extends AbstractController
{
    public function getResponse()
    {
        $response = new Response;
        $response->setContent(
            sprintf(
                '
Account Commands:
    %1$s account create <domain> <username> <password> <plan>

Email Commands:
    %1$s email create <email> <password> <quota>
    %1$s email changepassword <email> <password>
    %1$s email changequota <email> <quota>
                ',
                $this->getSlashCommand()
            )
        );

        return $response;
    }
}
