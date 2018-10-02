<?php

namespace Webdevvie\KontrolistoUtils\Slack;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AddChannelCommand
 * @package Webdevvie\KontrolistoUtils\Slack
 */
class AddChannelCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('add')
            ->setDescription('creates/updates a new channel');
        $this->addArgument("channel", InputArgument::OPTIONAL, 'the channel to make/update');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $input->getArgument('channel');
        if ($channel == '') {
            $channel = 'default';
        }
        $helper = $this->getHelper('question');
        $hookUrl = "https://";
        $question = new Question('Please enter the hook url: ', $hookUrl);
        $question->setMaxAttempts(null);
        $hookUrl = $helper->ask($input, $output, $question);
        $this->setChannelData($channel, ['hook' => $hookUrl]);
    }

    /**
     * @param string $channel
     * @param array  $cdata
     * @return void
     */
    public function setChannelData($channel, array $cdata)
    {
        $pharFile = \Phar::running(true);
        if ($pharFile != '') {
            $file = str_replace('.phar', '.yml', $pharFile);
            $file = str_replace('phar://', '', $file);
        } else {
            $file = __DIR__ . '/slack.yml';
        }
        if (file_exists($file)) {
            $data = Yaml::parseFile($file);
            if (is_null($data)) {
                $data = ["slack" => []];
            }
        } else {
            $data = ["slack" => []];
        }
        $data['slack'][$channel] = $cdata;
        file_put_contents($file, Yaml::dump($data, 10, 2));
    }
}
