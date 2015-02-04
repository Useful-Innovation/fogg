<?php namespace GoBrave\Fogg\Util;

use GoBrave\Fogg\Config;
use GoBrave\Util\Renderer;
use GoBrave\Util\SingPlur;
use GoBrave\Util\CaseConverter;

class Generator
{
  private $config;
  private $renderer;
  private $singplur;
  private $caser;

  public function __construct(Config $config, Renderer $renderer, SingPlur $singplur, CaseConverter $caser) {
    $this->config   = $config;
    $this->renderer = $renderer;
    $this->singplur = $singplur;
    $this->caser    = $caser;
  }

  public function model($name, $translatable = false, $force = false) {
    $file = rtrim($this->config->rootPath(), '/') . '/Models/' . $name . '.php';
    $this->newOrException($file, $force);
    $this->createDir(dirname($file));

    $vars = $this->defaultVars();
    $vars['namespace'] = $this->config->baseNamespace() . '\\Models';
    $vars['class']     = $name;
    $vars['uses']      = [];
    if(!$translatable) {
      $vars['parent'] = 'Model';
      $vars['uses'][] = $this->config->baseNamespace() . '\\System\\Model';
    } else {
      $vars['parent'] = 'Translatable';
      $vars['uses'][] = $this->config->baseNamespace() . '\System\Translatable';
    }

    file_put_contents(
      $file,
      $this->renderer->render('model', $vars)
    );



    //
    //    Translation
    //
    if(!$translatable) {
      return;
    }

    $file = rtrim($this->config->rootPath(), '/') . '/Models/' . $name . 'Translation.php';
    $this->newOrException($file, $force);
    $this->createDir(dirname($file));

    $vars['parent']    = 'Translation';
    $vars['uses']      = [$this->config->baseNamespace() . '\System\Translation'];
    $vars['class']     = $name . 'Translation';

    file_put_contents(
      $file,
      $this->renderer->render('model', $vars)
    );
  }

  public function controller($name, $translatable = false, $force = false) {
    $snake_singular = $this->caser->camelToSnake($name);
    $camel_singular = $name;
    $name = $this->singplur->pluralize($snake_singular);
    $name = $this->caser->snakeTocamel($name, true);

    $file = rtrim($this->config->rootPath(), '/') . '/Controllers/Admin/' . $name . 'Controller.php';
    $this->newOrException($file, $force);
    $this->createDir(dirname($file));

    $vars = $this->defaultVars();
    $vars['namespace']  = $this->config->baseNamespace() . '\\Controllers\\Admin';
    $vars['model_name'] = $camel_singular;
    $vars['plural']     = $this->caser->camelToSnake($name);
    $vars['singular']   = $snake_singular;
    $vars['class']      = $name . 'Controller';

    file_put_contents(
      $file,
      $this->renderer->render('admin_controller', $vars)
    );
  }

  public function views($name, $translatable = false, $force = false) {
    $name = $this->singplur->pluralize($this->caser->camelToSnake($name));
    $root = $this->config->viewsPath() . '/admin/' . $name;
    $this->createDir($root);

    $vars = $this->defaultVars();
    $vars['plural']       = $name;
    $vars['singular']     = $this->singplur->singularize($name);
    $vars['translatable'] = $translatable;

    foreach(['index', 'create', 'edit', '_form'] as $template) {
      $file = $root . '/' . $template . '.html.php';
      $this->newOrException($file, $force);
      file_put_contents(
        $file,
        $this->renderer->render('view.' . $template, $vars)
      );
    }
  }

  public function resource($name, $translatable = false, $force = false) {
    $this->controller($name, $translatable, $force);
    $this->model($name, $translatable, $force);
    $this->views($name, $translatable, $force);
  }









  //
  //
  //      Helpers
  //
  //
  private function newOrException($file, $force) {
    if(file_exists($file) AND $force === false) {
      throw new FileAlreadyExistsException('File \'' . $file . '\' already exists. Use --force to overwrite');
    }
  }

  private function createDir($dir) {
    system('mkdir -p ' . $dir);
  }

  private function defaultVars() {
    return [
      'php' => '<?php'
    ];
  }
}
