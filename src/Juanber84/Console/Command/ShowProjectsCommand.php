<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ShowProjectsCommand extends Command
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
            ->setName('show-projects')
            ->setDescription('List Deploy Projects');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $db = file_get_contents($_SERVER['HOME'].'/'.self::DIRECTORY.'/'.self::DB);
        $jsonDb = json_decode($db,true);
        if (is_null($jsonDb) || count($jsonDb) == 0) {
            $output->writeln('');
            $output->writeln('<info>0 projects configurated</info>');
        } else {
            $tableData = [];
            foreach ($jsonDb as $k =>$v) {
                $tableData[] = [$k,$v];
            }

            $table = new Table($output);
            $table
                ->setHeaders(array('Name', 'Path'))
                ->setRows($tableData)
            ;
            $table->render();
        }
    }
}