<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Question\ConfirmationQuestion;

trait ConfirmationProccessTrait
{
    public function confirmationProcess($input, $output, $helper, $message)
    {
        $question = new ConfirmationQuestion('Continue with this action? <info>Y/n</info> ', true);
        if (!$helper->ask($input, $output, $question)) {
            return $output->writeln("<fg=blue;>".$message.'</>');
        }
    }
}