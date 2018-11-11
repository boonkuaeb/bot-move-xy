#!/usr/bin/env php

<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\BotCommand;

$app = new Application();
$app->add(new BotCommand());
$app->run();