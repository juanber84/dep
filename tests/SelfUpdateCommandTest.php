<?php

use Juanber84\Console\Command\SelfUpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SelfUpdateCommandTest extends PHPUnit_Framework_TestCase
{
    public function testCurrentVersionEarliest()
    {
        $applicationService = $this->getMockBuilder('Juanber84\Services\ApplicationService')
            ->getMock();
        $applicationService
            ->method('currentTimeVersion')
            ->will($this->returnValue(125));

        $gitHubService = $this->getMockBuilder('\\Juanber84\\Services\\GitHubService')
            ->getMock();
        $gitHubService->method('latestTimeVersion')
            ->will($this->returnValue(120));

        $application = new Application();
        $application->add(new SelfUpdateCommand($applicationService, $gitHubService));

        $command = $application->find('self-update');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));
        $this->assertRegExp('/Lastest release is installed./', $commandTester->getDisplay());
    }

    public function testCurrentVersionLatestDownloadTrue()
    {
        $applicationService = $this->getMockBuilder('Juanber84\Services\ApplicationService')
            ->getMock();
        $applicationService
            ->method('currentTimeVersion')
            ->will($this->returnValue(120));

        $gitHubService = $this->getMockBuilder('\\Juanber84\\Services\\GitHubService')
            ->getMock();
        $gitHubService->method('latestTimeVersion')
            ->will($this->returnValue(125));

        $downloadService = $this->getMockBuilder('\\Juanber84\\Services\\DownloadService')
            ->getMock();
        $downloadService->method('download')
            ->will($this->returnValue(true));

        $question = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', array('ask'));
        $question->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue(true));

        $application = new Application();
        $application->add(new SelfUpdateCommand($applicationService, $gitHubService, $downloadService));
        $command = $application->find('self-update');
        $command->getHelperSet()->set($question, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/OK. Latest release was installed./', $commandTester->getDisplay());
    }

    public function testCurrentVersionLatestDownloadFalse()
    {
        $applicationService = $this->getMockBuilder('Juanber84\Services\ApplicationService')
            ->getMock();
        $applicationService
            ->method('currentTimeVersion')
            ->will($this->returnValue(120));

        $gitHubService = $this->getMockBuilder('\\Juanber84\\Services\\GitHubService')
            ->getMock();
        $gitHubService->method('latestTimeVersion')
            ->will($this->returnValue(125));

        $downloadService = $this->getMockBuilder('\\Juanber84\\Services\\DownloadService')
            ->getMock();
        $downloadService->method('download')
            ->will($this->returnValue(false));

        $question = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', array('ask'));
        $question->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue(true));

        $application = new Application();
        $application->add(new SelfUpdateCommand($applicationService, $gitHubService, $downloadService));
        $command = $application->find('self-update');
        $command->getHelperSet()->set($question, 'question');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('/KO. Error./', $commandTester->getDisplay());
    }

}
