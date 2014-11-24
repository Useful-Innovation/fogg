<?php namespace GoBrave\Fogg;

use GoBrave\Util\IWP;
use GoBrave\Util\Collection;
use GoBrave\Util\CaseConverter;
use GoBrave\Util\Renderer;

class Fogg
{
  private $config;
  private $wp;
  private $case_converter;
  private $renderer;

  public function __construct(Config $config, IWP $wp, CaseConverter $case_converter, Renderer $renderer) {
    $this->config         = $config;
    $this->wp             = $wp;
    $this->case_converter = $case_converter;
    $this->renderer       = $renderer;
  }

  public function boot() {
    if($this->wp->is_admin()) {
      $this->router = new Admin\Router($this->readRoutes('admin', 'resource'), $this->config, $this->wp, $this->case_converter, $this->renderer);
      $this->router->route(new Admin\Request($_GET, $_POST));
    } else {
      //$this->router = new Front\Router($this->readRoutes('public', 'resource'));
    }
  }


  private function readRoutes($key, $compare_key) {
    $routes = file_get_contents($this->config->routes());
    $routes = json_decode($routes);
    return new Collection($routes->{$key}, $compare_key);
  }

  public function config() {
    return $this->config;
  }
}
