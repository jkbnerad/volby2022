#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';

use app\Commands\About;
use app\Commands\ExportToCsv;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new About());
$application->add(new ExportToCsv());

$application->run();
