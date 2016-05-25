<?php

namespace Juanber84\Console\Command;

use Juanber84\Services\DatabaseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;

class AddProjectsCommand extends Command
{
    const COMMAND_NAME = 'add-project';
    const COMMAND_DESC = 'Add Deploy Project.';

    const DIRECTORY = '.dep';
    const DB = 'db.json';

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

        $question = new ConfirmationQuestion('Continue with this action? <info>Y/n</info> ', true);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        $jsonDb = $this->databaseService->getProjects();
        if (array_key_exists($nameOfProject,$jsonDb)) {
            $question = new ConfirmationQuestion('<error>This project exist. Do you want override it?</error> <info>Y/n</info> ', false);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }
        }

        if ($this->databaseService->addProject($nameOfProject, getcwd())){
            $output->writeln('<info>OK. Project added.</info>');
        } else {
            $output->writeln('<error>KO. Error</error>');
        }
    }
}