<?php namespace GoBrave\Fogg\Util;

use GoBrave\Fogg\Config;
use GoBrave\Util\Renderer;

class Generator
{
  private $config;
  private $renderer;

  public function __construct(Config $config, Renderer $renderer) {
    $this->config = $config;
    $this->renderer = $renderer;
  }

  public function controller($name, $options = [], $force = false) {
    $path  = rtrim($this->config->rootPath(), '/');
    $path .= '/Controllers';

    if(isset($options['namespace'])) {
      $path .= '/' . str_replace('\\', '/', $options['namespace']);
    }

    $file = $path . '/' . $name . '.php';
    if(file_exists($file) AND $force === false) {
      $tmp = str_replace(GR_ROOT_PATH . '/', '', $file);
      throw new FileAlreadyExistsException('File \'' . $tmp . '\' already exists. Use --force to overwrite');
    }
    system('mkdir -p ' . $path);
    file_put_contents($file, $this->renderController($name, $options));
  }



  public function model($name, $force = false) {
    $path  = rtrim($this->config->rootPath(), '/');
    $path .= '/Models';

    $file = $path . '/' . $name . '.php';
    if(file_exists($file) AND $force === false) {
      $tmp = str_replace(GR_ROOT_PATH . '/', '', $file);
      throw new FileAlreadyExistsException('File \'' . $tmp . '\' already exists. Use --force to overwrite');
    }
    system('mkdir -p ' . $path);
    file_put_contents($file, $this->renderModel($name));
  }













  //
  //
  //    Render functions
  //
  //
  private function renderController($name, $options) {
    $vars              = [];
    $vars['php']       = '<?php';
    $vars['class']     = $name;
    $vars['namespace'] = $this->config->baseNamespace() . '\\Controllers';
    if(isset($options['namespace'])) {
      $vars['namespace'] .= '\\' . $options['namespace'];
    }
    return $this->renderer->render('controller', $vars);
  }


  private function renderModel($name) {
    $vars              = [];
    $vars['php']       = '<?php';
    $vars['class']     = $name;
    $vars['namespace'] = $this->config->baseNamespace() . '\\Models';
    return $this->renderer->render('model', $vars);
  }
}
