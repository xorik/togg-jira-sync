#!/usr/bin/env php
<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

set_time_limit(0);

require __DIR__.'/vendor/autoload.php';

(new Dotenv())->loadEnv(__DIR__.'/.env');

$input = new ArgvInput();
$kernel = new Kernel('prod', false);
$application = new Application($kernel);
$application->setDefaultCommand('app:run', true);
$application->run($input);
