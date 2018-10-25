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

class BaseCommand extends Command {

  public function getConfig(string $file){
    if(!file_exists($file)) throw new \RuntimeException("Config file not found!");
    return json_decode(file_get_contents($file), true);
  }

  public function initialiseBuildContext(InputInterface $input){
    $config = $this->getConfig($input->getOption('config'));

    $this->buildContext = new BuildContext();
    foreach($config['paths'] as $name => $path){
      $this->buildContext->setPath($name, $path);
    }

    $this->buildContext->setEnv($input->getOption('environment'));

    $this->buildService = new BuildService($this->buildContext);

    $this->bladeBuilder = new BladeBuilder($this->buildContext);
    $this->bladeBuilder->setContext($input->getOption('context'));

    $this->assetBuilder = new AssetBuilder($this->buildContext);
    $this->assetBuilder->setCompression($input->getOption('compression'));

    $this->buildService->registerBuilder($this->assetBuilder);
    $this->buildService->registerBuilder($this->bladeBuilder);
  }
}
