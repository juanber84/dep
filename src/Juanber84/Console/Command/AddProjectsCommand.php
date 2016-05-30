<?php

namespace Juanber84\Console\Command;

use Juanber84\Services\DatabaseService;
use Juanber84\Texts\AddProjectsCommandText;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;

class AddProjectsCommand extends Command
{
    use NameProjectTrait;

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

        $nameOfProject = $this->getNameOfProject($input, $output, $helper, $nameOfProject);

        $question = new ConfirmationQuestion('Continue with this action? <info>Y/n</info> ', true);
        if (!$helper->ask($input, $output, $question)) {
            return $output->writeln("<fg=blue;>".AddProjectsCommandText::KO_ABORTED.'</>');
        }
        $jsonDb = $this->databaseService->getProjects();

        if (array_key_exists($nameOfProject,$jsonDb)) {
            $question = new ConfirmationQuestion('<error>This project exist. Do you want override it?</error> <info>Y/n</info> ', false);
            if (!$helper->ask($input, $output, $question)) {
                return $output->writeln("<fg=blue;>".AddProjectsCommandText::KO_ABORTED.'</>');
            }
        }

        if ($this->databaseService->addProject($nameOfProject, getcwd())){
            $output->writeln('<info>'.AddProjectsCommandText::OK_ADDED.'</info>');
        } else {
            $output->writeln('<error>'.AddProjectsCommandText::KO_ERROR.'</error>');
        }
    }
}