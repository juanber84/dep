<?php

use Symfony\Component\Console\Application;
use Juanber84\Console\Command\ShowProjectsCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Juanber84\Texts\ShowProjectsCommandText;

class ShowProjectsCommandTest extends PHPUnit_Framework_TestCase
{
    public function testNoProjectsConfigurated()
    {
        $databaseService = $this->getMockDatabaseService(null);

        $application = new Application();
        $application->add(new ShowProjectsCommand($databaseService));

        $command = $application->find(ShowProjectsCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/'.ShowProjectsCommandText::OK_0_PROJECTS.'/', $commandTester->getDisplay());
    }

    public function testTwoProjectsConfigurated()
    {
        $databaseService = $this->getMockDatabaseService(json_decode('{"p1":"\/Users\/juan.berzal\/dev\/p1\/backend\/source","p2":"\/Users\/juan.berzal\/dev\/p2\/backend\/source"}', true));

        $application = new Application();
        $application->add(new ShowProjectsCommand($databaseService));

        $command = $application->find(ShowProjectsCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/p1/', $commandTester->getDisplay());
        $this->assertRegExp('/p2/', $commandTester->getDisplay());
    }

    private function getMockDatabaseService($valueReturn)
    {
        $applicationService = $this->getMockBuilder('Juanber84\Services\DatabaseService')
            ->getMock();
        $applicationService
            ->method('getProjects')
            ->will($this->returnValue($valueReturn));

        return $applicationService;
    }
}
