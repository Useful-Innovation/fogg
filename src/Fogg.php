<?php namespace GoBrave\Fogg;

use GoBrave\Util\IWP;
use GoBrave\Util\Collection;
use GoBrave\Util\CaseConverter;
use GoBrave\Util\Renderer;
use GoBrave\Util\CropMode;

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

    if($this->wp->is_admin()) {
      $this->wp->wp_register_script('fogg', 
        $this->wp->get_bloginfo('template_url') . '/../vendor/gobrave/fogg/src/assets/fogg.js',
        false,
        false,
        true
      );
      $this->wp->wp_enqueue_script ('fogg');
    }


    \App\Config::$IMAGE_SIZES['fogg-thumbnail'] = [ 
      'width'     => 100,
      'height'    => 100,
      'crop-mode' => CropMode::HARD
    ];
  }

  public function boot() {
    if($this->wp->is_admin()) {
      $this->wp->wp_enqueue_media();
      $this->router = new Admin\Router($this->readRoutes('admin', 'resource'), $this->config, $this->wp, $this->case_converter, $this->renderer);
      $this->router->route(new Admin\Request($_GET, $_POST, $_FILES));
    } else {
      $this->router = new Front\Router(
        $this->readPublicRoutes('public'),
        $this->config,
        $this->wp,
        $this->case_converter,
        $this->renderer
      );
    }
  }

  public function route($vars) {
    return $this->router->route($vars);
  }

  private function readRoutes($key, $compare_key) {
    $routes = file_get_contents($this->config->routes());
    $routes = json_decode($routes);
    return new Collection($routes->{$key}, $compare_key);
  }

  private function readPublicRoutes($key) {
    $routes = file_get_contents($this->config->routes());
    $routes = json_decode($routes, true);
    return $routes[$key];
  }

  public function config() {
    return $this->config;
  }

  public function router() {
    return $this->router;
  }

  public function getRenderer() {
    return $this->renderer;
  }
}
