<?php

namespace Glomr\App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Glomr\App\Command\BaseCommand;
use Glomr\Watch\PollWatcher;
use Glomr\Watch\InotifyEventsWatcher;


class Watch extends BaseCommand {

  public function configure(){

    $this -> setName('watch')
      -> setDescription('Monitors the source directory and rebuilds project when files change')
      -> setHelp('Runs a full build of the project whenever a source file changes, optionally runs a web server to serv up the built html content')
      ->addOption('poll', '-p', InputOption::VALUE_OPTIONAL, 'Force use of PollerWatcher', 0)
      ->addOption('interval', '-i', InputOption::VALUE_OPTIONAL, 'Interval to use for watcher', 500)
      ->addOption('serv', '-s', InputOption::VALUE_OPTIONAL, 'Set to 0 to disable internal server', 1);
  }

  public function execute(InputInterface $input, OutputInterface $output){
    $this->initialiseBuildContext($input);
    $interval = $input->getOption('interval');
    $config = $this->getConfig($input->getOption('config'));

    if($input->getOption('serv') == 1){
      $this->buildService->runServer(
        $config['server']['address'],
        $config['server']['port'],
        $this->buildContext->getPath('build'),
        $config['server']['script']
      );
    }

    $usePoller = $input->getOption('poll') === 0 && defined('IN_MODIFY') ? false : true; 
    $this->buildService->watch($interval, $usePoller);
  }
}
