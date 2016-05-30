<?php


class DatabaseServiceTest extends PHPUnit_Framework_TestCase
{
    public function testGetDatabasePath()
    {
        $d = new \Juanber84\Services\DatabaseService();

        $reflection = new \ReflectionClass(get_class($d));
        $method = $reflection->getMethod('getDatabasePath');
        $method->setAccessible(true);

        $this->assertRegExp('/'.$d::DB.'/',$method->invoke($d));
        $this->assertRegExp('/'.$d::DIRECTORY.'/',$method->invoke($d));
    }
    
    public function testGenerateDatabase()
    {
        $d = new \Juanber84\Services\DatabaseService();

        $reflection = new \ReflectionClass(get_class($d));
        $methodGenerateDatabase = $reflection->getMethod('generateDatabase');
        $methodGenerateDatabase->setAccessible(true);
        $methodDatabasePath = $reflection->getMethod('getDatabasePath');
        $methodDatabasePath->setAccessible(true);
        $methodGenerateDatabase->invoke($d);

        $this->assertFileExists($methodDatabasePath->invoke($d));
    }
}
