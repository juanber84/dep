<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class AddProjectsCommand extends Command
{
    const COMMAND_NAME = 'add-project';
    const COMMAND_DESC = 'Add Deploy Project.';

    const DIRECTORY = '.dep';
    const DB = 'db.json';

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESC);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        // Project question
        $question = new Question('<question>What is the project key?</question>: ');
        do {
            $nameOfProject = trim($helper->ask($input, $output, $question));
        } while (empty($nameOfProject));

        $question = new ConfirmationQuestion('Continue with this action? <info>Y/n</info> ', false);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        if (!file_exists($_SERVER['HOME'].'/'.self::DIRECTORY)) {
            mkdir($_SERVER['HOME'].'/'.self::DIRECTORY, 0777, true);
        }

        $db = file_get_contents($_SERVER['HOME'].'/'.self::DIRECTORY.'/'.self::DB);
        $jsonDb = json_decode($db,true);
        if (is_null($jsonDb)) $jsonDb = array();

        if (array_key_exists($nameOfProject,$jsonDb)) {
            $question = new ConfirmationQuestion('<error>This project exist. Do you want override it?</error> <info>Y/n</info> ', false);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }
        }
        $jsonDb[$nameOfProject] = getcwd();
        file_put_contents($_SERVER['HOME'].'/'.self::DIRECTORY.'/'.self::DB, json_encode($jsonDb));

        $output->writeln('');
        $output->writeln('<info>Ok</info>');
    }
}