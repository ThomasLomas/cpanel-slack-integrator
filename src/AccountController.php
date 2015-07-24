<?php

use Symfony\Component\HttpFoundation\Response;

class AccountController extends AbstractController
{
    public function getResponse()
    {
        $response = new Response;
        $response->setContent('
            Work in progress
        ');

        return $response;
    }
}
