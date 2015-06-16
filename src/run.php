#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Command\SendCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new SendCommand());
$application->run();
