<?php

namespace grigor\generator\services;

use grigor\generator\writer\SettingsWriter;
use Psr\Container\ContainerInterface;

class ApiService
{
    private $container;
    private $settingsWriter;

    /**
     * ApiService constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container, SettingsWriter $settingsWriter)
    {
        $this->container = $container;
        $this->settingsWriter = $settingsWriter;
    }

}