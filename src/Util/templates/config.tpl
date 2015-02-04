//
//    Eloquent database ORM
//
$capsule = new Illuminate\Database\Capsule\Manager();
$capsule->addConnection([
  'driver'    => 'mysql',
  'host'      => DB_HOST,
  'database'  => DB_NAME,
  'username'  => DB_USER,
  'password'  => DB_PASSWORD,
  'charset'   => 'utf8',
  'collation' => 'utf8_swedish_ci',
  'prefix'    => 'fogg_'
]);

$capsule->setEventDispatcher(new Illuminate\Events\Dispatcher(new Illuminate\Container\Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();





//
//    The Fogg system for admin and public routes, models etc when dealing with external data in Wordpress
//
$fogg = new GoBrave\Fogg\Fogg(
  new GoBrave\Fogg\Config([
    'routes'            => GR_APP_PATH . '/Fogg/routes.json',
    'base_namespace'    => 'App\Fogg',
    'root_path'         => GR_APP_PATH . '/Fogg',
    'admin_page_name'   => 'Fogg',
    'controller_suffix' => 'Controller',
    'views_path'        => GR_APP_PATH  . '/templates/fogg'
  ]),
  new GoBrave\Util\Wp(),
  new GoBrave\Util\CaseConverter(),
  new GoBrave\Fogg\System\Renderer([
      GR_APP_PATH  . '/templates/fogg',
      GR_ROOT_PATH . '/vendor/gobrave/fogg/src/Views'
    ], 'html'
  )
);
$fogg->boot();
App\App::setFilter('route', [$fogg, 'route']);

if(is_admin()) {
  wp_enqueue_script('jquery-ui-autocomplete');
}
