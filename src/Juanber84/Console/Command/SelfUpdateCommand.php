<?php

namespace Juanber84\Console\Command;

use Juanber84\Services\ApplicationService;
use Juanber84\Services\DownloadService;
use Juanber84\Services\GitHubService;
use Juanber84\Texts\SelfUpdateCommandText;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class SelfUpdateCommand extends Command
{
    const COMMAND_NAME = 'self-update';
    const COMMAND_DESC = 'Update Dep application to last release.';

    private $applicationService;

    private $gitHubService;

    private $downloadService;

    public function __construct(ApplicationService $applicationService, GitHubService $gitHubService, DownloadService $downloadService)
    {
        parent::__construct();

        $this->applicationService = $applicationService;
        $this->gitHubService = $gitHubService;
        $this->downloadService = $downloadService;
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESC);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currentVersion = $this->applicationService->currentTimeVersion();
        $latestVersion = $this->gitHubService->latestTimeVersion();

        if ($currentVersion < $latestVersion) {

            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Continue with this action? <question>Y/n</question>', true);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }

            if ($this->downloadService->download($this->gitHubService->latestBrowserDownloadUrl())){
                $output->writeln('<info> '.SelfUpdateCommandText::OK_INSTALLED.'</info>');
            } else {
                $output->writeln('<info> '.SelfUpdateCommandText::KO_INSTALLED.'</info>');
            }

        } else {
            $output->writeln('<info> '.SelfUpdateCommandText::OK_CURRENT.'</info>');
        }
    }
}