<?php

namespace Webdevvie\KontrolistoUtils\Slack;

use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Class HelpCommand
 * @package Webdevvie\KontrolistoUtils\Slack
 */
class SendCommand extends Command
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('send')
            ->setDescription('Sends std-in to slack');
        $this->addArgument("channel", InputArgument::OPTIONAL, 'the channel to use', 'default');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \Exception
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = file_get_contents("php://stdin");
        $this->output = $output;
        if ($text == '') {
            $output->writeln("<error>Your input text is empty</error>");
        }
        $data = ['text' => $text];
        $channel = $input->getArgument('channel');
        if ($channel == '') {
            $channel = 'default';
        }
        $channelData = $this->getChannelData($channel);
        if (is_null($channelData)) {
            $output->writeln("<error> Invalid channel $channel</error>");
            return;
        }
        $outData = [
            "text" => $data['text']
        ];
        $body = json_encode($outData, JSON_PRETTY_PRINT);
        try {
            $client = new Client();
            $client->post($channelData['hook'], ['body' => $body, 'headers' => ['Content-Type' => "application/json"]]);
        } catch (\Exception $exception) {
            $output->writeln("FAIL");
            if ($input->getOption("verbose")) {
                $output->writeln("<error>" . $exception->getMessage() . "</error>");
            }
            return;
        }
        $output->writeln("OK");
        return;
    }

    /**
     * @param string $channel
     * @return null|array
     */
    public function getChannelData($channel)
    {
        $pharFile = \Phar::running(true);
        if ($pharFile != '') {
            $file = str_replace('.phar', '.yml', $pharFile);
            $file = str_replace('phar://', '', $file);
        } else {
            $file = __DIR__ . '/slack.yml';
        }
        if (!file_exists($file)) {
            $this->output->writeln("<error>$file does not exist</error>");
            return null;
        }
        $data = Yaml::parseFile($file);
        if (isset($data['slack'][$channel])) {
            return $data['slack'][$channel];
        }
        return null;
    }
}
