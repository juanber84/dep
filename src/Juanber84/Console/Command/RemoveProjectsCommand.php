<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Juanber84\Services\DatabaseService;
use Juanber84\Texts\RemoveProjectsCommandText;
use Symfony\Component\Console\Input\InputArgument;

class RemoveProjectsCommand extends Command
{
    const COMMAND_NAME = 'remove-project';
    const COMMAND_DESC = 'Remove Deploy Project.';

    private $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        parent::__construct();

        $this->databaseService = $databaseService;
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESC)
            ->addArgument(
                'project',
                InputArgument::OPTIONAL,
                'What\'s the name of project?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $nameOfProject = $input->getArgument('project');
        if (!$nameOfProject) {
            $question = new Question('<question>What is the project key?</question>: ');
            do {
                $nameOfProject = trim($helper->ask($input, $output, $question));
            } while (empty($nameOfProject));
        }

        $jsonDb = $this->databaseService->getProjects();

        if (is_null($jsonDb)) {
            $output->writeln('');
            $output->writeln('<info>'.RemoveProjectsCommandText::OK_0_PROJECTS.'</info>');
        } else {
            $helperConfirm = $this->getHelper('question');
            $question = new ConfirmationQuestion('Continue with this action? <question>Y/n</question> ', true);
            if (!$helperConfirm->ask($input, $output, $question)) {
                return $output->writeln('<info>'.RemoveProjectsCommandText::KO_ABORTED.'</info>');
            }

            if ($this->databaseService->removeProject($nameOfProject)) {
                $output->writeln('<info>'.RemoveProjectsCommandText::OK_REMOVED.'</info>');
            } else {
                $output->writeln('<info>'.RemoveProjectsCommandText::KO_EXIST.'</info>');
            }
        }
    }

}