<?php

require __DIR__ . '/vendor/autoload.php';

use Secondtruth\Compiler\Compiler;

date_default_timezone_set('UTC');
$version = time() + (60 * 5); // Add 5 minutes to prevent problems with same release
$content = '<?php

function getVersion()
{
    return '.$version.';
}
';

file_put_contents('./settings.php', $content);

$compiler = new Compiler(__DIR__);

$compiler->addIndexFile('console.php');

$compiler->addFile('vendor/autoload.php');
$compiler->addFile('settings.php');
$compiler->addDirectory('vendor/composer', '!*.php');
$compiler->addDirectory('vendor/symfony', '!*.php');
$compiler->addDirectory('src', '!*.php');

$compiler->compile("dep.phar");
