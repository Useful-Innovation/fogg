<?php namespace GoBrave\Fogg;

class Config
{
  private $routes;
  private $base_namespace;
  private $admin_page_name;
  private $controller_suffix;
  private $root_path;
  private $views_path;

  public function __construct($config = []) {
    foreach(get_object_vars($this) as $key => $value) {
      if(isset($config[$key])) {
        $this->{$key} = $config[$key];
      }
    }
  }

  public function routes() {
    return $this->routes;
  }

  public function baseNamespace() {
    return $this->base_namespace;
  }

  public function adminPageName() {
    return $this->admin_page_name;
  }

  public function controllerSuffix() {
    return $this->controller_suffix;
  }

  public function rootPath() {
    return $this->root_path;
  }

  public function viewsPath() {
    return $this->views_path;
  }
}
