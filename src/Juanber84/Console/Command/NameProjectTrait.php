<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Question\Question;

trait NameProjectTrait
{
    private function getNameOfProject($input, $output, $helper, $nameOfProject)
    {
        if (!$nameOfProject) {
            $question = new Question('<question>What is the project key?</question>: ');
            do {
                $nameOfProject = trim($helper->ask($input, $output, $question));
            } while (empty($nameOfProject));
        }

        return $nameOfProject;
    }

}