<?php

namespace Website\Bootstrap;

use SVCodebase\Library\CliBootstrap;

class Cli extends CliBootstrap
{
    public function initServices()
    {
        parent::initDB();
    }
}
