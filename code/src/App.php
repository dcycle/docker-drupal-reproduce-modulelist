<?php

namespace myproject;

use myproject\traits\Environment;
use myproject\traits\Singleton;

/**
 * Encapsulated code for the application.
 */
class App {

  use Environment;
  use Singleton;

  /**
   * Print all Drupal modules and projects to screen.
   *
   * @throws Exception
   */
  public function printDrupalModules() {
    $contents = $this->file('/app/module-list/my-modules.txt');
    $dl = [];
    $en = [];
    foreach ($contents as $line) {
      $this->downloadString($line, $dl);
      $this->enString($line, $en);
    }

    foreach ($dl as $item) {
      $this->print($item . PHP_EOL);
    }
    if (count($en)) {
      $this->print('drush en -y' . PHP_EOL);
      foreach ($en as $item) {
        $this->print($item . PHP_EOL);
      }
    }
  }

  /**
   * Populates the $dl array based on a line of output from the database.
   */
  public function downloadString($line, &$dl) {
    $processed = $this->process($line);
    if (empty($processed['project'])) {
      return;
    }
    if (!$processed['is-core']) {
      $dl[md5($processed['project'])] = 'drush dl ' . $processed['project'] . '-' . $processed['version'];
    }
  }

  /**
   * Populates the $en array based on a line of output from the database.
   */
  public function enString($line, &$en) {
    $processed = $this->process($line);
    if (empty($processed['name'])) {
      return;
    }
    $en[md5($processed['name'])] = 'drush en -y ' . $processed['name'];
  }

  /**
   * Processes a line of output from "select filename,info from system".
   */
  public function process(string $line) : array {
    $return = [];

    $matches = [];
    preg_match('/^[^\s]*/', $line, $matches);
    $return['path'] = $matches[0];

    $matches = [];
    preg_match('/a:.*$/', $line, $matches);
    $return['info'] = $matches[0];
    $return['parsed-info'] = unserialize($return['info']);
    $return['version'] = $return['parsed-info']['version'];
    $return['project'] = $return['parsed-info']['project'];

    $matches = [];
    preg_match('/\/([^\.\/]*)\./', $return['path'], $matches);
    $return['name'] = $matches[1];
    $return['is-core'] = ($return['project'] == 'drupal');
    return $return;
  }

  /**
   * Prints the square root of the number of files in a directory.
   *
   * This method is admittedly useless, but it is meant to demonstrate how
   * a method which uses externalities via the Environment trait can be
   * tested.
   *
   * @throws \Exception
   */
  public function printSquareRootOfNumberOfFilesInDirectory() {
    $directory = $this->getEnv('DIRECTORY');
    if (!$directory) {
      throw new \Exception('Please set the DIRECTORY environment variable.');
    }
    $files = $this->scanDir($directory);
    $sqrt = $this->squareRoot(count($files));
    $this->print($sqrt);
  }

  /**
   * Returns the square root of a number.
   *
   * This is added to show an example of how the associated test code
   * (see ./code/test/AppTest.php) works for a pure function with no
   * externalities.
   *
   * @param float $a
   *   The number whose square root we want to get.
   *
   * @return float
   *   The square root.
   *
   * @throws \Exception
   */
  public function squareRoot(float $a) : float {
    if ($a < 0) {
      throw new \Exception('No square root for negative numbers.');
    }
    return sqrt($a);
  }

}
