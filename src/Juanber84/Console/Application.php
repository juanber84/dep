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

        return '<info>Automatic deploy tool </info>'.$actualVersion;
    }
}