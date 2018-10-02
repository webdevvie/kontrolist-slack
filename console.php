<?php
if (Phar::running()) {
    Phar::interceptFileFuncs();
}
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    //either console or phar
    require_once(__DIR__ . '/vendor/autoload.php');
} else {
    //we're a package today
    require_once(__DIR__ . '/../../autoload.php');
}

use Symfony\Component\Console\Application;

// {{VERSION}}
$application = new Application();
$application->add(new \Webdevvie\KontrolistoUtils\Slack\HelpCommand());
$application->add(new \Webdevvie\KontrolistoUtils\Slack\ReadmeCommand());
$application->add(new \Webdevvie\KontrolistoUtils\Slack\SendCommand());
$application->add(new \Webdevvie\KontrolistoUtils\Slack\AddChannelCommand());
$application->add(new \Webdevvie\KontrolistoUtils\Slack\KontrolistoAutoConfigCommand());
$application->setDefaultCommand('help');
$application->run();
