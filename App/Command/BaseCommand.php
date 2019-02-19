<?php

namespace Glomr\App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Glomr\Build\BuildContext;
use Glomr\Build\BuildService;
use Glomr\Build\BladeBuilder;
use Glomr\Build\AssetBuilder;

use Glomr\Log\Logr;

use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Adapter\Local;

class BaseCommand extends Command {

  public function getConfig(string $file){
    if(!file_exists($file)) throw new \RuntimeException("Config file not found!");
    $config = json_decode(file_get_contents($file), true);
    if(
      !array_key_exists('paths', $config) ||
      !array_key_exists('server', $config) ||
      !array_key_exists('interval', $config) ||
      !array_key_exists('source', $config['paths']) ||
      !array_key_exists('build', $config['paths']) ||
      !array_key_exists('cache', $config['paths']) ||
      !array_key_exists('address', $config['server']) ||
      !array_key_exists('port', $config['server']) ||
      !array_key_exists('script', $config['server']) 

    ){
      throw new \RuntimeExceptions("Invalid condig - missing key");
    }
    return $config;
  }

  public function initialiseBuildContext(InputInterface $input){
    $config = $this->getConfig($input->getOption('config'));

    $this->buildContext = new BuildContext(
        new Flysystem(new Local(getcwd())),
        $config['paths']['source'],
        $config['paths']['build'],
        $config['paths']['cache'],
    );

    $this->buildContext->setEnv($input->getOption('environment'));

    $this->buildService = new BuildService($this->buildContext);

    $this->bladeBuilder = new BladeBuilder($this->buildContext, ['context' => $input->getOption('context')]);

    $this->assetBuilder = new AssetBuilder($this->buildContext, ['compression' => $input->getOption('compression')]);

    $this->buildService->registerBuilder($this->assetBuilder);
    $this->buildService->registerBuilder($this->bladeBuilder);
  }
}
