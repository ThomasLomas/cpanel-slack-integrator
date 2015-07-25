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

            case 'changepassword':
                return $this->changePassword();
                break;

            case 'changequota':
                return $this->changeQuota();
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

        $accountUsername = $this->getAccountUserFromEmail($email);

        if(!$accountUsername) {
            return new Response('Account not found');
        }

        list($user, $domain) = explode('@', $email);

        $create = $this->xmlapi->addpop(
            $accountUsername,
            array($user, $password, $quota, $domain)
        );

        if(isset($create->error)) {
            return new Response((string)$create->error);
        }

        return new Response(
            sprintf(
                '%s has been created',
                $email
            )
        );
    }

    protected function changePassword()
    {
        $arguments = $this->getArguments();

        if(count($arguments) !== 3) {
            return new Response('Please specify email and password');
        }

        list($command, $email, $password) = $arguments;

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response('Invalid email');
        }

        $accountUsername = $this->getAccountUserFromEmail($email);

        if(!$accountUsername) {
            return new Response('Account not found');
        }

        list($user, $domain) = explode('@', $email);

        $quota = $this->xmlapi->getpopquota(
            $accountUsername,
            array($user, $domain)
        );

        $quota = ($quota->data->result == 'unlimited') ? 0 : (int) $quota->data->result;

        $changePassword = $this->xmlapi->passwdpop(
            $accountUsername,
            array($user, $password, $quota, $domain)
        );

        if(isset($changePassword->error)) {
            return new Response((string)$changePassword->error);
        }

        return new Response(
            sprintf(
                '%s password has been updated',
                $email
            )
        );
    }

    protected function changeQuota()
    {
        $arguments = $this->getArguments();

        if(count($arguments) !== 3) {
            return new Response('Please specify email and quota');
        }

        list($command, $email, $quota) = $arguments;

        $quota = (int)$quota;

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response('Invalid email');
        }

        $accountUsername = $this->getAccountUserFromEmail($email);

        if(!$accountUsername) {
            return new Response('Account not found');
        }

        list($user, $domain) = explode('@', $email);

        $changeQuota = $this->xmlapi->editpopquota(
            $accountUsername,
            array($user, $domain, $quota)
        );

        if(isset($changeQuota->error)) {
            return new Response((string)$changeQuota->error);
        }

        return new Response(
            sprintf(
                '%s quota has been updated',
                $email
            )
        );
    }

    /**
     * Gets the account username from an email address
     * @param  string $email
     * @return null|string
     */
    protected function getAccountUserFromEmail($email)
    {
        list($user, $domain) = explode('@', $email);

        $userData = $this->xmlapi->domainuserdata($domain);

        if($userData->result->status == 0) {
            return null;
        }

        return (string) $userData->userdata->user;
    }
}
