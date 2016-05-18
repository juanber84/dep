<?php

require __DIR__ . '/vendor/autoload.php';

use Secondtruth\Compiler\Compiler;
use Symfony\Component\Yaml\Dumper;

$array = array(
    'version' => time()
);

$dumper = new Dumper();

$yaml = $dumper->dump($array);

file_put_contents('./settings.yml', $yaml);

$compiler = new Compiler(__DIR__);

$compiler->addIndexFile('console.php');

$compiler->addFile('vendor/autoload.php');
$compiler->addDirectory('vendor/composer', '!*.php');
$compiler->addDirectory('vendor/symfony', '!*.php');
$compiler->addDirectory('src', '!*.php');

$compiler->compile("dep.phar");
