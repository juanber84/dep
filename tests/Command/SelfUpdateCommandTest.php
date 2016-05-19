<?php

use Juanber84\Console\Command\SelfUpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Juanber84\Texts\SelfUpdateCommandText;

class SelfUpdateCommandTest extends PHPUnit_Framework_TestCase
{
    public function testCurrentVersionEarliest()
    {
        $applicationService = $this->getMockApplicationService(125);
        $gitHubService = $this->getMockGitHubService(120);

        $application = new Application();
        $application->add(new SelfUpdateCommand($applicationService, $gitHubService));

        $command = $application->find(SelfUpdateCommand::COMMAND_NAME);
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/'.SelfUpdateCommandText::OK_CURRENT.'/', $commandTester->getDisplay());
    }

    public function testCurrentVersionLatestDownloadTrue()
    {
        $applicationService = $this->getMockApplicationService(120);
        $gitHubService = $this->getMockGitHubService(125);
        $downloadService = $this->getMockDownloadService(true);
        $question = $this->getMockQuestionHelper(true);

        $application = new Application();
        $application->add(new SelfUpdateCommand($applicationService, $gitHubService, $downloadService));

        $command = $application->find(SelfUpdateCommand::COMMAND_NAME);
        $command->getHelperSet()->set($question, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/'.SelfUpdateCommandText::OK_INSTALLED.'/', $commandTester->getDisplay());
    }

    public function testCurrentVersionLatestDownloadFalse()
    {
        $applicationService = $this->getMockApplicationService(120);
        $gitHubService = $this->getMockGitHubService(125);
        $downloadService = $this->getMockDownloadService(false);
        $question = $this->getMockQuestionHelper(true);

        $application = new Application();
        $application->add(new SelfUpdateCommand($applicationService, $gitHubService, $downloadService));

        $command = $application->find(SelfUpdateCommand::COMMAND_NAME);
        $command->getHelperSet()->set($question, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/'.SelfUpdateCommandText::KO_INSTALLED.'/', $commandTester->getDisplay());
    }

    private function getMockApplicationService($valueReturn)
    {
        $applicationService = $this->getMockBuilder('Juanber84\Services\ApplicationService')
            ->getMock();
        $applicationService
            ->method('currentTimeVersion')
            ->will($this->returnValue($valueReturn));

        return $applicationService;
    }

    private function getMockGitHubService($valueReturn)
    {
        $gitHubService = $this->getMockBuilder('\Juanber84\Services\GitHubService')
            ->getMock();
        $gitHubService->method('latestTimeVersion')
            ->will($this->returnValue($valueReturn));

        return $gitHubService;
    }

    private function getMockDownloadService($valueReturn)
    {
        $downloadService = $this->getMockBuilder('\Juanber84\Services\DownloadService')
            ->getMock();
        $downloadService->method('download')
            ->will($this->returnValue($valueReturn));

        return $downloadService;
    }

    private function getMockQuestionHelper($valueReturn)
    {
        $question = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', array('ask'));
        $question->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue($valueReturn));

        return $question;
    }
}
