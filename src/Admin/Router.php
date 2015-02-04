<?php namespace GoBrave\Fogg\Admin;

use GoBrave\Util\Collection;
use GoBrave\Util\IWP;
use GoBrave\Util\CaseConverter;
use GoBrave\Util\Renderer;
use GoBrave\Fogg\Config;
use GoBrave\Fogg\Admin\Session;
use GoBrave\Fogg\System\Response;

class Router
{
  const KEY_EDIT   = 'fogg-edit';
  const KEY_NEW    = 'fogg-new';
  const KEY_DELETE = 'fogg-delete';

  private $routes;
  private $config;
  private $wp;
  private $case_converter;
  private $renderer;
  private $session;

  private $route;
  private $vars;

  public function __construct(Collection $routes, Config $config, IWP $wp, CaseConverter $case_converter, Renderer $renderer) {
    $this->routes         = $routes;
    $this->config         = $config;
    $this->wp             = $wp;
    $this->case_converter = $case_converter;
    $this->renderer       = $renderer;
    $this->session        = new Session();

    $this->preparePages();
  }

  public function route(Request $request) {
    if(!$request->get('page') OR !isset($this->routes[$request->get('page')])) {
      return;
    }

    $this->route = new Route($this->config, $this->routes[$request->get('page')]);
    $this->route->requestMethod($_SERVER['REQUEST_METHOD']);
    $this->route->controller($this->case_converter->snakeToCamel($request->get('page'), true));

    if($request->get(Route::TYPE_EDIT)) {
      $this->route->type(Route::TYPE_EDIT);
    } else if($request->get(Route::TYPE_CREATE)) {
      $this->route->type(Route::TYPE_CREATE);
    } else if($request->get(Route::TYPE_DELETE)) {
      $this->route->type(Route::TYPE_DELETE);
    } else {
      $this->route->type(Route::TYPE_INDEX);
    }

    $this->route->prepare();
    $this->renderer->setDefaultVars(['route' => $this->route, 'session' => $this->session]);

    $this->wp->add_action('admin_init', function() use($request) {

      $controller = $this->route->controllerClass();
      $controller = new $controller($this->session, $this->renderer);

      $controller->runBeforeFilters($this->case_converter->snakeToCamel($this->route->method(), true));
      $this->response = $controller->{$this->route->method()}($request);
      $controller->runAfterFilters($this->case_converter->snakeToCamel($this->route->method(), true));

      $this->response = $this->response ?: new Response();

      if($this->response->type() === Response::JSON) {
        echo json_encode($this->response->vars(), JSON_PRETTY_PRINT);
        exit;
      }
    });
  }

  public function render() {
    echo $this->renderer->render(
      'admin/' . $this->route->resource()->resource . '/' . $this->route->method(),
      $this->response->vars()
    );
    $this->session->flush();
  }



  //
  //  Setup admin pages
  //
  private function preparePages() {
    $this->wp->add_action('admin_menu', function() {
      $this->wp->add_menu_page(
        $this->config->adminPageName(),
        $this->config->adminPageName(),
        'edit_posts',
        'fogg',
        'fogg'
      );

      foreach($this->routes as $key => $value) {
        $this->wp->add_submenu_page(
          'fogg',
          $value->plural,
          $value->plural,
          'edit_posts',
          $value->resource,
          [$this, 'render']
        );
      }
    });
  }
}
