<?php namespace GoBrave\Fogg\Util;

use GoBrave\Util\Renderer;
use GoBrave\Util\SingPlur;
use GoBrave\Util\CaseConverter;
use WP_CLI;

/**
 * Fogg Generator. Generates classes and views for Fogg
 */
class WPCLI
{



  /**
   * Generate a controller
   * @synopsis --name=<ResourceName> [--translatable] [--force]
   */
  public function controller($positional, $assoc) {
    $this->runGenerator('controller', $assoc);
    WP_CLI::success('Controller ' . $assoc['name'] . ' generated');
  }



  /**
   * Generate a model
   * @synopsis --name=<ResourceName> [--translatable] [--force]
   */
  public function model($positional, $assoc) {
    $this->runGenerator('model', $assoc);
    WP_CLI::success('Model ' . $assoc['name'] . ' generated');
  }




  /**
   * Generate views
   * @synopsis --name=<ResourceName> [--translatable] [--force]
   */
  public function views($positional, $assoc) {
    $this->runGenerator('views', $assoc);
    WP_CLI::success('Views for ' . $assoc['name'] . ' generated');
  }





  /**
   * Generate a resource (Controller, Model and views)
   * @synopsis --name=<ResourceName> [--translatable] [--force]
   */
  public function resource($positional, $assoc) {
    $this->controller($positional, $assoc);
    $this->model($positional, $assoc);
    $this->views($positional, $assoc);
  }




  /**
   * Setup Front and AdminController + Model and routes
   * @synopsis [--force]
   */
  public function setup($positional, $assoc) {
    $config = $this->getConfig();
    system('mkdir -p ' . $config->rootPath() . '/System');
    system('cp ' . __DIR__ . '/files/*.php ' . $config->rootPath() . '/System/');
    system('cp ' . __DIR__ . '/files/routes.json ' . $config->rootPath() . '/');
  }




  /**
   * Outputs an example config for functions.php
   */
  public function config($positional, $assoc) {
    WP_CLI::log(PHP_EOL . file_get_contents(__DIR__ . '/templates/config.tpl'));
    WP_CLI::success('Put this config in functions.php');
  }




  //
  //    Helpers
  //
  private function runGenerator($method, $assoc) {
    $generator = $this->generator();
    try {
      $generator->{$method}($assoc['name'], (bool)@$assoc['translatable'], (bool)@$assoc['force']);
    } catch (FileAlreadyExistsException $e) {
      WP_CLI::error($e->getMessage());
    }
  }

  private function generator() {
    return new Generator($this->getConfig(), $this->renderer(), new SingPlur(), new CaseConverter());
  }

  private function getConfig() {
    global $fogg;
    return $fogg->config();
  }

  private function renderer() {
    return new Renderer(__DIR__ . '/templates', 'tpl');
  }
}
