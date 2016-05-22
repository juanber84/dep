<?php

namespace Juanber84\Console;

use Juanber84\Console\Command\BatchProcessCommand;
use Juanber84\Console\Command\SelfUpdateCommand;
use Juanber84\Services\ApplicationService;
use Juanber84\Services\GitHubService;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{

    const MESSAGE_NAME = "Automatic deploy tool";
    const MESSAGE_UPDATE = "New release available. Please execute";

    private static $logo = "
  .----------------.  .----------------.  .----------------.
| .--------------. || .--------------. || .--------------. |
| |  ________    | || |  _________   | || |   ______     | |
| | |_   ___ `.  | || | |_   ___  |  | || |  |_   __ \\   | |
| |   | |   `. \\ | || |   | |_  \\_|  | || |    | |__) |  | |
| |   | |    | | | || |   |  _|  _   | || |    |  ___/   | |
| |  _| |___.' / | || |  _| |___/ |  | || |   _| |_      | |
| | |________.'  | || | |_________|  | || |  |_____|     | |
| |              | || |              | || |              | |
| '--------------' || '-------------- ' || '--------------' |
 '----------------'  '----------------'  '----------------'
";

    private $applicationService;

    private $gitHubService;

    public function __construct(ApplicationService $applicationService, GitHubService $gitHubService)
    {
        parent::__construct();

        $this->applicationService = $applicationService;
        $this->gitHubService = $gitHubService;
    }

    public function getHelp()
    {
        return self::$logo . parent::getHelp();
    }

    public function getLongVersion()
    {
        $actualVersion = $this->applicationService->currentTimeVersion();
        $latestVersion = $this->gitHubService->latestTimeVersion();

        $message = '';
        if ($actualVersion < $latestVersion) {
            $message .= "\n <bg=yellow;fg=black;options=bold>".self::MESSAGE_UPDATE." ".SelfUpdateCommand::COMMAND_NAME." to install.</>\n";
        }

        $message .="\n <info>".self::MESSAGE_NAME." </info>".$actualVersion;

        return $message;
    }
}