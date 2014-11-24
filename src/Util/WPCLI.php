<?php namespace GoBrave\Fogg\Util;

use GoBrave\Util\Renderer;

class WPCLI
{
  /**
   * Imports a specific post-type
   * @synopsis --name=<ControllerName> [--namespace=<Namespace>] [--force]
   */
  public function controller($positional, $assoc) {
    $generator = $this->generator();
    try {
      $generator->controller($assoc['name'], [
        'namespace' => @$assoc['namespace']
      ], (bool)@$assoc['force']);
    } catch (FileAlreadyExistsException $e) {
      \WP_CLI::error($e->getMessage());
    }
  }



  /**
   * Imports a specific post-type
   * @synopsis --name=<ModelName> [--force]
   */
  public function model($positional, $assoc) {
    $generator = $this->generator();
    try {
      $generator->model($assoc['name'], (bool)@$assoc['force']);
    } catch (FileAlreadyExistsException $e) {
      \WP_CLI::error($e->getMessage());
    }
  }

  public function views() {
    
  }




  //
  //    Helpers
  //
  private function generator() {
    return new Generator($this->config(), $this->renderer());
  }

  private function config() {
    global $fogg;
    return $fogg->config();
  }

  private function renderer() {
    return new Renderer(__DIR__ . '/templates', 'tpl');
  }
}
