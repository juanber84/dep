<?php

namespace Juanber84\Console\Command;

use Juanber84\Services\ApplicationService;
use Juanber84\Services\GitHubService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class SelfUpdateCommand extends Command
{

    const DEVELOP = 'develop';
    const STAGING = 'staging';
    const QUALITY = 'quality';
    const MASTER  = 'master';

    const DIRECTORY = '.dep';
    const DB = 'db.json';

    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setDescription('Update Dep application to last release.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $actualVersion = (new ApplicationService())->currentTimeVersion();
        $latestRelease = (new GitHubService())->lastestRelease();
        $latestVersion = (new GitHubService())->lastestTimeVersion();

        if ($actualVersion < $latestVersion)
        {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Continue with this action?<question>Y/n</question>', true);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }
            $content = file_get_contents($latestRelease['assets'][0]['browser_download_url']);
            file_put_contents("./newdep.phar", $content);
            unlink('./dep.phar');
            $content = file_get_contents("./newdep.phar");
            file_put_contents("dep.phar", $content);
            unlink('./newdep.phar');

            $output->writeln('<info>Ok. Latest release was installed.</info>');
        } else {
            $output->writeln('<info>Lastest release is installed.</info>');
        }

    }
}