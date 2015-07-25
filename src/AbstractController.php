<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    protected $request;
    protected $config;
    protected $xmlapi;
    protected $command;
    protected $arguments;
    protected $slashCommand;

    public function checkCredentials()
    {
        $token = $this->request->get('token');

        if(null === $token || $token != $this->config['slack_token'] || $this->request->getMethod() !== 'POST') {
            $response = new Response('Request not authorized', Response::HTTP_UNAUTHORIZED);
            $response->send();
            die();
        }

        $this->xmlapi = new XMLAPI($this->config['ip'], $this->config['username'], $this->config['password']);
        $this->xmlapi->set_port($this->config['port']);
        $this->xmlapi->set_protocol($this->config['protocol']);

        return $this;
    }

    /**
     * Gets the value of request.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets the value of request.
     *
     * @param Request $request the request
     *
     * @return self
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Gets the value of config.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the value of config.
     *
     * @param array $config the config
     *
     * @return self
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Gets the value of xmlapi.
     *
     * @return mixed
     */
    public function getXmlapi()
    {
        return $this->xmlapi;
    }

    /**
     * Sets the value of xmlapi.
     *
     * @param mixed $xmlapi the xmlapi
     *
     * @return self
     */
    public function setXmlapi($xmlapi)
    {
        $this->xmlapi = $xmlapi;

        return $this;
    }

    /**
     * Gets the value of command.
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Sets the value of command.
     *
     * @param string $command the command
     *
     * @return self
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Gets the value of arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Sets the value of arguments.
     *
     * @param array $arguments the arguments
     *
     * @return self
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Gets the value of slashCommand.
     *
     * @return string
     */
    public function getSlashCommand()
    {
        return $this->slashCommand;
    }

    /**
     * Sets the value of slashCommand.
     *
     * @param string $slashCommand the slash command
     *
     * @return self
     */
    public function setSlashCommand($slashCommand)
    {
        $this->slashCommand = $slashCommand;

        return $this;
    }
}
