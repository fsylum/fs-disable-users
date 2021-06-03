<?php

namespace Fsylum\DisableUsers;

use Fsylum\DisableUsers\Contracts\Service;

class Plugin
{
    protected $services = [];

    public function addService(Service $service)
    {
        $this->services[] = $service;
    }

    public function run()
    {
        foreach ($this->services as $service) {
            (new $service)->run();
        }
    }
}
