<?php

namespace myproject\traits;

/**
 * Wrapper around elements external to the application logic.
 */
trait Environment {

  /**
   * Mockable wrapper around getenv(), with exception handling.
   *
   * @param string $name
   *   Name of the environment variable.
   * @param bool $required
   *   Whether the environment variable is required or not.
   *
   * @return string
   *   The value of the environment variable.
   *
   * @throws \Exception
   */
  public function getEnv(string $name, bool $required = TRUE) : string {
    $return = getenv($name);
    if (!$return && $required) {
      throw new \Exception('Please make sure the environment variable ' . $name . ' is set.');
    }
    return (string) $return;
  }

  /**
   * Mockable wrapper around get_file_contents().
   */
  protected function file($f) {
    return file($f);
  }

  /**
   * Mockable wrapper around scandir(), with exception handling.
   *
   * @param string $directory
   *   Path to a directory.
   *
   * @return array
   *   List of files.
   *
   * @throws \Exception
   */
  public function scanDir(string $directory) : array {
    $return = @scandir($directory);
    if (!is_array($return)) {
      throw new \Exception('Could not scan directory ' . $directory);
    }
    return $return;
  }

  /**
   * Mockable wrapper around print().
   */
  public function print($string) {
    print($string);
  }

  /**
   * Mockable wrapper around print_r().
   */
  public function printR($mixed) {
    print_r($mixed);
  }

}
