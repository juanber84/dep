<?php

/**
 * Created by IntelliJ IDEA.
 * User: juan.berzal
 * Date: 23/5/16
 * Time: 15:09
 */
class ApplicationServiceTest extends PHPUnit_Framework_TestCase
{
    public function testCurrentVersion()
    {
        $applicationService = new \Juanber84\Services\ApplicationService();
        $this->assertEquals(strtotime('10 September 2000'),$applicationService->currentTimeVersion());
    }
}
