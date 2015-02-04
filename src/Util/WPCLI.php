<?php namespace GoBrave\Fogg\Util;

use GoBrave\Util\Renderer;
use GoBrave\Util\SingPlur;
use GoBrave\Util\CaseConverter;

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
    \WP_CLI::success('Controller ' . $assoc['name'] . ' generated');
  }



  /**
   * Generate a model
   * @synopsis --name=<ResourceName> [--translatable] [--force]
   */
  public function model($positional, $assoc) {
    $this->runGenerator('model', $assoc);
    \WP_CLI::success('Model ' . $assoc['name'] . ' generated');
  }




  /**
   * Generate views
   * @synopsis --name=<ResourceName> [--translatable] [--force]
   */
  public function views($positional, $assoc) {
    $this->runGenerator('views', $assoc);
    \WP_CLI::success('Views for ' . $assoc['name'] . ' generated');
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




  //
  //    Helpers
  //
  private function runGenerator($method, $assoc) {
    $generator = $this->generator();
    try {
      $generator->{$method}($assoc['name'], (bool)@$assoc['translatable'], (bool)@$assoc['force']);
    } catch (FileAlreadyExistsException $e) {
      \WP_CLI::error($e->getMessage());
    }
  }

  private function generator() {
    return new Generator($this->config(), $this->renderer(), new SingPlur(), new CaseConverter());
  }

  private function config() {
    global $fogg;
    return $fogg->config();
  }

  private function renderer() {
    return new Renderer(__DIR__ . '/templates', 'tpl');
  }
}
