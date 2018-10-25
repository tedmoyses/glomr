<?php

namespace Glomr\App\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Glomr\App\Command\BaseCommand;
use Glomr\Watch\PollWatcher;
use Glomr\Watch\InotifyEventsWatcher;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Glomr\Log\Logr;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Init extends BaseCommand {
  public function configure (){
    $this -> setName('init')
      -> setDescription('Initialises a Glomr project, choose a style of site')
      -> setHelp('This DESTRUCTIVE command will replace the contents of source directory with a chosen example project')
      -> addOption('project', null, InputOption::VALUE_REQUIRED, 'Choose a project', ['Single Page', 'Multi Page']);
  }

  public function execute(InputInterface $input, OutputInterface $output){
    $helper = $this->getHelper('question');
    $examplesPath = dirname(dirname(__DIR__)) . '/examples';
    $choices = array_map(function($d) {return basename($d);}, glob($examplesPath . '/*', GLOB_ONLYDIR));

    $initChoice = new ChoiceQuestion("Please choose which starter site to initialise with", $choices, 0);
    $initChoice->setErrorMessage("Example choice %s is invalid.");
    $choice = $helper->ask($input, $output, $initChoice);

    $confirm = new ConfirmationQuestion("This command is destructive! Are you sure? (y/n)", false);
    if($helper->ask($input, $output, $confirm)){
      // ok - here we clear out the src directory and replace it with a an example
      $this->initialiseBuildContext($input);

      $srcPath = $this->buildContext->getPath('source');
      $buildPath = $this->buildContext->getPath('build');
      $cachePath = $this->buildContext->getPath('cache');
      if($this->rm_r($srcPath) && $this->rm_r($buildPath) && $this->rm_r($cachePath)){
        // we have deleted the src directory - time to add it back with an example
        mkdir($cachePath. '/blade', 0777, true);
        mkdir($cachePath. '/assets', 0777, true);
        $this->xcopy($examplesPath.'/'.$choice, $this->buildContext->getPath('source'));
        $this->buildService->build();
      }
    }
  }

  /**
 * Copy a file, or recursively copy a folder and its contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       int      $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        $this->xcopy("$source/$entry", "$dest/$entry", $permissions);
    }

    // Clean up
    $dir->close();
    return true;
}

  /**
   * Recursively delete a directory and all of it's contents - e.g.the equivalent of `rm -r` on the command-line.
   * Consistent with `rmdir()` and `unlink()`, an E_WARNING level error will be generated on failure.
   *
   * @param string $dir absolute path to directory to delete
   *
   * @return bool true on success; false on failure
   */
  function rm_r($dir)
  {
      if (false === is_dir($dir)) {
          return false;
      }

      /** @var SplFileInfo[] $files */
      $files = new RecursiveIteratorIterator(
          new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
          RecursiveIteratorIterator::CHILD_FIRST
      );
      foreach ($files as $fileinfo) {
          if ($fileinfo->isDir()) {
              if (false === rmdir($fileinfo->getRealPath())) {
                  return false;
              }
          } else {
              if (false === unlink($fileinfo->getRealPath())) {
                  return false;
              }
          }
      }
      return rmdir($dir);
  }
}
