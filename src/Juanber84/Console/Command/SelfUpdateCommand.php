<?php

namespace Juanber84\Console\Command;

use Juanber84\Services\ApplicationService;
use Juanber84\Services\DownloadService;
use Juanber84\Services\GitHubService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class SelfUpdateCommand extends Command
{
    private $applicationService;

    private $gitHubService;

    private $downloadService;

    public function __construct($applicationService = null, $gitHubService = null, $downloadService = null)
    {
        parent::__construct();

        $this->applicationService = (is_null($applicationService)) ? (new ApplicationService()) : $applicationService;
        $this->gitHubService = (is_null($gitHubService)) ? (new GitHubService()) : $gitHubService;
        $this->downloadService = (is_null($downloadService)) ? (new DownloadService()) : $downloadService;
    }

    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setDescription('Update Dep application to last release.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currentVersion = $this->applicationService->currentTimeVersion();
        $latestVersion = $this->gitHubService->latestTimeVersion();

        if ($currentVersion < $latestVersion)
        {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Continue with this action? <question>Y/n</question>', true);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }

            if ($this->downloadService->download($this->gitHubService->latestBrowserDownloadUrl())){
                $output->writeln('<info> OK. Latest release was installed.</info>');
            } else {
                $output->writeln('<info> KO. Error.</info>');
            }

        } else {
            $output->writeln('<info> Lastest release is installed.</info>');
        }
    }
}