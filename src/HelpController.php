<?php

use Symfony\Component\HttpFoundation\Response;

class HelpController extends AbstractController
{
    public function getResponse()
    {
        $response = new Response;
        $response->setContent('
            cPanel Commands:
            /cpanel account create <domain> <username> <password> <package>
            /cpanel email create <email> <password> <quota>
        ');

        return $response;
    }
}
