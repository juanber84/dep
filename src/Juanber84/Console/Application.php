<?php

namespace Juanber84\Console;

use Juanber84\Console\Command\BatchProcessCommand;
use Juanber84\Console\Command\SelfUpdateCommand;
use Juanber84\Services\ApplicationService;
use Juanber84\Services\GitHubService;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{

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
| '--------------' || '--------------' || '--------------' |
 '----------------'  '----------------'  '----------------'
";
    public function getHelp()
    {
        return self::$logo . parent::getHelp();
    }

    public function getLongVersion()
    {
        $actualVersion = (new ApplicationService())->currentTimeVersion();
        $latestVersion = (new GitHubService())->latestTimeVersion();

        $message = '';
        if ($actualVersion < $latestVersion) {
            $message .= "\n <bg=yellow;fg=black;options=bold>New release available. Please execute ".SelfUpdateCommand::COMMAND_NAME." to install.</>\n";
        }

        $message .="\n <info>Automatic deploy tool </info>".$actualVersion;

        return $message;
    }
}