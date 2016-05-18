<?php

namespace Juanber84\Console;

use Juanber84\Console\Command\BatchProcessCommand;
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
        if ('UNKNOWN' !== $this->getName()) {
            if ('UNKNOWN' !== $this->getVersion()) {
                return sprintf('<info>%s</info> version <comment>%s</comment>', $this->getName(), $this->getVersion());
            }

            return sprintf('<info>%s</info>', $this->getName());
        }

        $actualVersion = getVersion();

        $url = 'https://api.github.com/repos/juanber84/dep/releases/latest';

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch,CURLOPT_USERAGENT,'Awesome-Octocat-App');
        $data = curl_exec($ch);
        curl_close($ch);

        $latestRelease = json_decode($data, true);
        $latestVersion = strtotime($latestRelease['created_at']);

        $message = '';
        if ($actualVersion < $latestVersion)
        {
            $message .= "\n <bg=yellow;fg=black;options=bold>New release available. Please execute self-update to install.</>\n";
        }

        $message .="\n <info>Automatic deploy tool </info>".$actualVersion;

        return $message;
    }
}