<?php

namespace Webdevvie\KontrolistoUtils\Slack;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HelpCommand
 * @package Webdevvie\KontrolistoUtils\Slack
 */
class HelpCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('help')
            ->setDescription('Displays a help message');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(file_get_contents(__DIR__ . '/help.txt'));
        $arguments = array(
            'command' => 'list',
            '--help'
        );
        $this->getApplication()->find('list')->run(new ArrayInput($arguments), $output);
    }
}
