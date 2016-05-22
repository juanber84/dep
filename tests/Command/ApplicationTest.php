<?php

use Juanber84\Console\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testCurrentVersionOutdated()
    {
        $applicationService = $this->getMockApplicationService(120);
        $gitHubService = $this->getMockGitHubService(125);

        $application = new Application($applicationService, $gitHubService);
        $this->assertRegexp('/'.Application::MESSAGE_UPDATE.'/', $application->getLongVersion());
    }

    public function testCurrentVersionUpdated()
    {
        $applicationService = $this->getMockApplicationService(125);
        $gitHubService = $this->getMockGitHubService(125);

        $application = new Application($applicationService, $gitHubService);
        $this->assertNotContains(Application::MESSAGE_UPDATE, $application->getLongVersion());
    }

    public function testApplicationSuccess()
    {

        $applicationService = $this->getMockApplicationService(125);
        $gitHubService = $this->getMockGitHubService(120);

        $application = new Application($applicationService, $gitHubService);
        $inputMock = $this->getMock('Symfony\Component\Console\Input\InputInterface');
        $outputMock = $this->getMock('Symfony\Component\Console\Output\OutputInterface');

        $this->assertEquals(0,$application->doRun($inputMock, $outputMock));
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
}
