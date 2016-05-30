<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Juanber84\Console\Command\AddProjectsCommand;
use Juanber84\Texts\AddProjectsCommandText;

class AddProjectsCommandTest extends PHPUnit_Framework_TestCase
{

    public function testFalseQuestionsDatabaseNull()
    {
        $questionNameProject = $this->getMockQuestionConfirmHelper(false);
        $databaseService = $this->getMockDatabaseService(array());

        $application = new Application();
        $application->add(new AddProjectsCommand($databaseService));

        $command = $application->find(AddProjectsCommand::COMMAND_NAME);
        $command->getHelperSet()->set($questionNameProject, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => 'fake'
        ));

        $this->assertRegExp('/'.AddProjectsCommandText::KO_ABORTED.'/', $commandTester->getDisplay());
    }

    public function testYesQuestionsDatabaseReturnTrue()
    {
        $questionNameProject = $this->getMockQuestionConfirmHelper(true);
        $databaseService = $this->getMockDatabaseService(array(), true);

        $application = new Application();
        $application->add(new AddProjectsCommand($databaseService));

        $command = $application->find(AddProjectsCommand::COMMAND_NAME);
        $command->getHelperSet()->set($questionNameProject, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => 'fake'
        ));

        $this->assertRegExp('/'.AddProjectsCommandText::OK_ADDED.'/', $commandTester->getDisplay());
    }

    public function testYesQuestionsDatabaseReturnFalse()
    {
        $questionNameProject = $this->getMockQuestionConfirmHelper(true);
        $databaseService = $this->getMockDatabaseService(array(), false);

        $application = new Application();
        $application->add(new AddProjectsCommand($databaseService));

        $command = $application->find(AddProjectsCommand::COMMAND_NAME);
        $command->getHelperSet()->set($questionNameProject, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => 'fake'
        ));

        $this->assertRegExp('/'.AddProjectsCommandText::KO_ERROR.'/', $commandTester->getDisplay());
    }

    public function testYesQuestionsSecondTruQuestionDatabaseReturnFalse()
    {
        $questionNameProject = $this->getMockQuestionConfirmHelper(true,false);
        $databaseService = $this->getMockDatabaseService(array('fake'=>'/'), false);

        $application = new Application();
        $application->add(new AddProjectsCommand($databaseService));

        $command = $application->find(AddProjectsCommand::COMMAND_NAME);
        $command->getHelperSet()->set($questionNameProject, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'project' => 'fake'
        ));

        $this->assertRegExp('/'.AddProjectsCommandText::KO_ABORTED.'/', $commandTester->getDisplay());
    }

    private function getMockDatabaseService($valueReturnGetProjects, $valueReturnRemoveProject = null)
    {
        $applicationService = $this->getMockBuilder('Juanber84\Services\DatabaseService')
            ->getMock();
        $applicationService
            ->method('getProjects')
            ->will($this->returnValue($valueReturnGetProjects));
        $applicationService
            ->method('addProject')
            ->will($this->returnValue($valueReturnRemoveProject));

        return $applicationService;
    }

    private function getMockQuestionConfirmHelper($valueReturn, $secondeValueReturn = null)
    {
        $question = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', array('ask'));
        $question
            ->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue($valueReturn));

        if ($secondeValueReturn){
            $question
                ->expects($this->at(1))
                ->method('ask')
                ->will($this->returnValue($secondeValueReturn));
        }

        return $question;
    }

}
