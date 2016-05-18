<?php

require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('UTC');

$console = new \Juanber84\Console\Application();
$console->addCommands(array(
    new \Juanber84\Console\Command\ShowProjectsCommand(),
    new \Juanber84\Console\Command\AddProjectsCommand(),
    new \Juanber84\Console\Command\RemoveProjectsCommand(),
    new \Juanber84\Console\Command\DeployProjectsCommand(),
    new \Juanber84\Console\Command\SelfUpdateCommand()
));
$console->run();
