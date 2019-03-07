<?php

require_once './vendor/autoload.php';

use Glomr\App\Application;
use Glomr\App\Command\Build;
use Symfony\Component\Console\Input\ArrayInput;

$app = new Application();
$app->add(new Build);
$input = new ArrayInput(['command' => 'build']);
$app ->run($input);

