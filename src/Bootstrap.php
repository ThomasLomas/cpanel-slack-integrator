<?php

require __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$config = Config::getConfig();

$arguments = explode(' ', $request->get('text'));
$command = array_shift($arguments);

switch(strtolower($command)) {
    case 'account':
        $controller = new AccountController;
        break;

    case 'email':
        $controller = new EmailController;
        break;

    case 'help':
    default:
        $controller = new HelpController;
        break;
}

$controller
    ->setRequest($request)
    ->setConfig($config)
    ->setCommand($command)
    ->setArguments($arguments)
    ->checkCredentials()
;

$response = $controller->getResponse();
$response->send();
