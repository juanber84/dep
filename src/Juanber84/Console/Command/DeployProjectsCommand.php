<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputArgument;

class DeployProjectsCommand extends Command
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
            ->setName('deploy-project')
            ->setDescription('Auto Deploy Project')
            ->addArgument(
                'project',
                InputArgument::OPTIONAL,
                'What\'s the name of project?'
            )
            ->addArgument(
                'branch',
                InputArgument::OPTIONAL,
                'What\'s the name of branch?'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hieranchy = [self::DEVELOP, self::STAGING, self::QUALITY, self::MASTER];
        $validBranchs = [self::STAGING, self::QUALITY, self::MASTER];
        $nameOfProject = $input->getArgument('project');
        $branchOfProject = $input->getArgument('branch');
        $helper = $this->getHelper('question');

        if (!$nameOfProject) {
            $question = new Question('<question>What is the project key?</question>: ');
            do {
                $nameOfProject = trim($helper->ask($input, $output, $question));
            } while (empty($nameOfProject));
        }

        if (!$branchOfProject) {
            $question = new Question('<question>What is the branch to deploy?</question>: ');
            do {
                $branchOfProject = trim($helper->ask($input, $output, $question));
            } while (empty($branchOfProject) || !in_array($branchOfProject, $validBranchs));
        }

        $db = file_get_contents(getenv("HOME").'/'.self::DIRECTORY.'/'.self::DB);
        $jsonDb = json_decode($db,true);

        if (is_null($jsonDb)) {
            $output->writeln('');
            $output->writeln('<info>0 projects configurated</info>');
        } else {
            if (isset($jsonDb[$nameOfProject])) {
                $merge = self::DEVELOP;
                $final = self::STAGING;
                foreach ($hieranchy as $k => $v) {
                    if ($v == $branchOfProject) {
                        $merge = $hieranchy[$k-1];
                        $final = $v;
                    }
                }
                $task = 'cd '.$jsonDb[$nameOfProject];
                $task .= ' && git checkout ' .$merge;
                $task .= ' && git pull';
                $task .= ' && git checkout ' .$final;
                $task .= ' && git merge '.$merge;
                $task .= ' && git push';
                $task .= ' && git checkout develop';
                echo $task."\n";
                echo $console = shell_exec($task);
            }

        }

        $output->writeln('');
        $output->writeln('<info>Ok</info>');
    }
}