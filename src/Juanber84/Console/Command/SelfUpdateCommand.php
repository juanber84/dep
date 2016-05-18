<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Yaml\Parser;

class SelfUpdateCommand extends Command
{

    const DEVELOP = 'develop';
    const STAGING = 'staging';
    const QUALITY = 'quality';
    const MASTER  = 'master';

    const DIRECTORY = '.dep';
    const DB = 'db.json';

    protected function configure()
    {
        $this
            ->setName('self-update')
            ->setDescription('Update Dep application to last release.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $yaml = new Parser();
        $settings = $yaml->parse(file_get_contents('./settings.yml'));
        $actualVersion = $settings['version'];

        $url = 'https://api.github.com/repos/juanber84/dep/releases/latest';

        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch,CURLOPT_USERAGENT,'Awesome-Octocat-App');
        $data = curl_exec($ch);
        curl_close($ch);

        $latestRelease = json_decode($data, true);
        $latestVersion = strtotime($latestRelease['created_at']);

        if ($actualVersion < $latestVersion)
        {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Continue with this action?<question>Y/n</question>', true);
            if (!$helper->ask($input, $output, $question)) {
                return;
            }
            $content = file_get_contents($latestRelease['assets'][0]['browser_download_url']);
            file_put_contents("./newdep.phar", $content);
            unlink('./dep.phar');
            $content = file_get_contents("./newdep.phar");
            file_put_contents("dep.phar", $content);
            unlink('./newdep.phar');

            $output->writeln('<info>Ok. Latest release was installed.</info>');
        } else {
            $output->writeln('<info>Lastest release is installed.</info>');
        }

    }
}