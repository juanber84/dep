<?php

namespace Juanber84\Console\Command;

use Symfony\Component\Console\Question\ConfirmationQuestion;

trait ConfirmationProccessTrait
{
    public function confirmationProcess($input, $output, $helper, $message, $messageQuestion = 'Continue with this action? <info>Y/n</info> ')
    {
        $question = new ConfirmationQuestion($messageQuestion, true);
        if (!$helper->ask($input, $output, $question)) {
            return $output->writeln("<fg=blue;>".$message.'</>');
        }
    }
}