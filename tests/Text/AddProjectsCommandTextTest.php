<?php

class AddProjectsCommandTextTest extends PHPUnit_Framework_TestCase
{
    public function testTexts()
    {
        $class = new \Juanber84\Texts\AddProjectsCommandText();
        $reflection = new \ReflectionClass(get_class($class));
        $consts = $reflection->getConstants();
        $this->assertTrue(isset($consts['KO_ABORTED']));
        $this->assertTrue(isset($consts['OK_ADDED']));
        $this->assertTrue(isset($consts['KO_ERROR']));
    }
}
