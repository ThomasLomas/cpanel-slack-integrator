<?php

use Symfony\Component\Yaml\Yaml;

class Config
{
    public static function getConfig()
    {
        $config = Yaml::parse(
            file_get_contents(__DIR__ . '/../config/parameters.yml')
        );

        return $config['config'];
    }
}
