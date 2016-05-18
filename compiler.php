<?php

require __DIR__ . '/vendor/autoload.php';

use Secondtruth\Compiler\Compiler;
use Symfony\Component\Yaml\Dumper;

date_default_timezone_set('UTC');
$array = array(
    'version' => time() + (60 * 30) // Add 30 minutes to prevent problems with same release
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
