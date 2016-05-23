<?php

require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('UTC');

$applicationService = new \Juanber84\Services\ApplicationService();
$gitHubService = new \Juanber84\Services\GitHubService();
$downloadService = new \Juanber84\Services\DownloadService();
$databaseService = new \Juanber84\Services\DatabaseService();

$console = new \Juanber84\Console\Application($applicationService, $gitHubService);
$console->addCommands(array(
    new \Juanber84\Console\Command\ShowProjectsCommand($databaseService),
    new \Juanber84\Console\Command\AddProjectsCommand(),
    new \Juanber84\Console\Command\RemoveProjectsCommand($databaseService),
    new \Juanber84\Console\Command\DeployProjectsCommand(),
    new \Juanber84\Console\Command\SelfUpdateCommand(
        $applicationService, $gitHubService, $downloadService
    )
));
$console->run();
