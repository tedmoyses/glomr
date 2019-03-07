<?php

namespace Glomr\App;

use Symfony\Component\Console\Application as App;
use Glomr\Log\Logr;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * This class only exists to add global option for debugging by extending the parent
 * application class and overriding two methods with slight modifications
 */
class Application extends App {

  /**
   * Overdide the Symfony console application method to add a new default application
   * option of -d debug
   * @return Array Holds all global options for application
   */
  public function getDefaultInputDefinition () {
    $inputOptions = parent::getDefaultInputDefinition();
    $inputOptions->addOption(new InputOption('--debug', '-d', InputOption::VALUE_NONE, 'Enable debug messages'));
    $inputOptions->addOption(new InputOption('--config', '-c', InputOption::VALUE_OPTIONAL, 'Set config filepath', 'config.json'));
    $inputOptions->addOption(new InputOption('--context', '-t', InputOption::VALUE_OPTIONAL, 'Set context for source templates', 'templates'));
    $inputOptions->addOption(new InputOption('--compression', '', InputOption::VALUE_OPTIONAL, 'Set level of asset compression \'low\', \'high\' or \'\'', ''));
    $inputOptions->addOption(new InputOption('--nocolour', '-nc', InputOption::VALUE_NONE, 'Disable colourised output'));
    $inputOptions->addOption(new InputOption('--environment', '-e', InputOption::VALUE_OPTIONAL, 'Set environment to dev or production - controls how assets get produced', 'dev'));
    return $inputOptions;
  }

  /**
   * Overrides the parent doRun method to evaluate if we have a -d option present
   * If so we set the logr to use debug level
   * Additionally we create an empty config if it doesn't exist
   * @param  InputInterface  $input  holds input options
   * @param  OutputInterface $output holds output options
   * @return int                  returns parent class value for exit code
   */
  public function doRun(InputInterface $input, OutputInterface $output) {
    if (true === $input->hasParameterOption(['--debug', '-d'], false, true)) {
      Logr::setDebug(true);
    }
    if (true === $input->hasParameterOption(['--nocolour', '-nc'], false, true)) {
      Logr::setColour(false);
    }


    // check for a config file - make the default if not
    $configPath = $input->getParameterOption('--config', 'config.json', true);
    if(!file_exists($configPath)){
      file_put_contents($configPath, '{
	"paths": {
		"source": ".\/src",
		"build": ".\/build",
		"cache": ".\/cache",
		"context": "templates"
	},
	"server": {
		"address": "0.0.0.0",
		"port": 8080,
		"script": ""
	},
	"interval": 500
      }');
    }
    return parent::doRun($input, $output);
  }

}
