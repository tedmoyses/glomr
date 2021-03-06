<?php

namespace Glomr\App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Glomr\App\Command\BaseCommand;

class Build extends BaseCommand {

  public function configure(){
    $this -> setName('build')
      -> setDescription('Triggers a single build of the source files')
      -> setHelp('Building iterates over all files and directories in the source directory and builds them according to the registered builders. The resulting html is in the build directory');
  }

  public function execute(InputInterface $input, OutputInterface $output){
		$this->initialiseBuildContext($input);

    $config = $this->getConfig($input->getOption('config'));

		if(array_key_exists('vars', $config)){
			$vars = $config['vars'];
		} else {
			$vars = [];
		}

		$this->buildService->build($vars);
  }
}
