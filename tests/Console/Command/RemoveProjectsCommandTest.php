<?php

use Symfony\Component\Console\Application;
use Juanber84\Console\Command\RemoveProjectsCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Juanber84\Texts\RemoveProjectsCommandText;

class RemoveProjectsCommandTest extends PHPUnit_Framework_TestCase
{
    public function testNoProjectsConfigurated()
    {
        $databaseService = $this->getMockDatabaseService(null);

        $application = new Application();
        $application->add(new RemoveProjectsCommand($databaseService));

        $command = $application->find(RemoveProjectsCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => 'fake'
        ));

        $this->assertRegExp('/'.RemoveProjectsCommandText::OK_0_PROJECTS.'/', $commandTester->getDisplay());
    }

    public function testProjectsConfiguratedAbortedProcess()
    {
        $databaseService = $this->getMockDatabaseService(json_decode('{"p1":"\/Users\/juan.berzal\/dev\/p1\/backend\/source","p2":"\/Users\/juan.berzal\/dev\/p2\/backend\/source"}', true));
        $questionConfirm = $this->getMockQuestionConfirmHelper(false);

        $application = new Application();
        $application->add(new RemoveProjectsCommand($databaseService));

        $command = $application->find(RemoveProjectsCommand::COMMAND_NAME);
        $command->getHelperSet()->set($questionConfirm, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => 'fake'
        ));

        $this->assertRegExp('/'.RemoveProjectsCommandText::KO_ABORTED.'/', $commandTester->getDisplay());
    }

    public function testProjectsConfiguratedProjectNotExist()
    {
        $databaseService = $this->getMockDatabaseService(json_decode('{"p1":"\/Users\/juan.berzal\/dev\/p1\/backend\/source","p2":"\/Users\/juan.berzal\/dev\/p2\/backend\/source"}', true), false);
        $questionConfirm = $this->getMockQuestionConfirmHelper(true);

        $application = new Application();
        $application->add(new RemoveProjectsCommand($databaseService));

        $command = $application->find(RemoveProjectsCommand::COMMAND_NAME);
        $command->getHelperSet()->set($questionConfirm, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => 'fake'
        ));

        $this->assertRegExp('/'.RemoveProjectsCommandText::KO_EXIST.'/', $commandTester->getDisplay());
    }

    public function testProjectsConfiguratedProjectExist()
    {
        $databaseService = $this->getMockDatabaseService(json_decode('{"p1":"\/Users\/juan.berzal\/dev\/p1\/backend\/source","p2":"\/Users\/juan.berzal\/dev\/p2\/backend\/source"}', true), true);
        $questionConfirm = $this->getMockQuestionConfirmHelper(true);

        $application = new Application();
        $application->add(new RemoveProjectsCommand($databaseService));

        $command = $application->find(RemoveProjectsCommand::COMMAND_NAME);
        $command->getHelperSet()->set($questionConfirm, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => 'p1'
        ));

        $this->assertRegExp('/'.RemoveProjectsCommandText::OK_REMOVED.'/', $commandTester->getDisplay());
    }

    private function getMockDatabaseService($valueReturnGetProjects, $valueReturnRemoveProject = null)
    {
        $applicationService = $this->getMockBuilder('Juanber84\Services\DatabaseService')
            ->getMock();
        $applicationService
            ->method('getProjects')
            ->will($this->returnValue($valueReturnGetProjects));
        $applicationService
            ->method('removeProject')
            ->will($this->returnValue($valueReturnRemoveProject));

        return $applicationService;
    }

    private function getMockQuestionConfirmHelper($valueReturn)
    {
        $question = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', array('ask'));
        $question->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue($valueReturn));

        return $question;
    }
}
