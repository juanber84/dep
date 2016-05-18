<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class RemoveProjectsCommand extends Command
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
            ->setName('remove-project')
            ->setDescription('Remove Deploy Project');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        // Project question
        $question = new Question('<question>What is the project key?</question>: ');
        do {
            $nameOfProject = trim($helper->ask($input, $output, $question));
        } while (empty($nameOfProject));

        $db = file_get_contents($_SERVER['HOME'].'/'.self::DIRECTORY.'/'.self::DB);
        $jsonDb = json_decode($db,true);

        if (is_null($jsonDb)) {
            $output->writeln('');
            $output->writeln('<info>0 projects configurated</info>');
        } else {
            $question = new ConfirmationQuestion('Continue with this action?<question>Y/n</question>', false);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }
            unset($jsonDb[$nameOfProject]);
            file_put_contents($_SERVER['HOME'].'/'.self::DIRECTORY.'/'.self::DB, json_encode($jsonDb));
        }

        $output->writeln('');
        $output->writeln('<info>Ok</info>');
    }
}