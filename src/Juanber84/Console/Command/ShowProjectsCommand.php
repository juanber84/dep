<?php

namespace Juanber84\Console\Command;

use Juanber84\Services\DatabaseService;
use Juanber84\Texts\ShowProjectsCommandText;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ShowProjectsCommand extends Command
{
    const COMMAND_NAME = 'show-projects';
    const COMMAND_DESC = 'List Deploy Projects.';

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
            ->setDescription(self::COMMAND_DESC);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonDb = $this->databaseService->getProjects();
        if (is_null($jsonDb) || count($jsonDb) == 0) {
            $output->writeln('');
            $output->writeln('<info>'.ShowProjectsCommandText::OK_0_PROJECTS.'</info>');
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