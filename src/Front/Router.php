<?php namespace GoBrave\Fogg\Front;

use GoBrave\Fogg\Config;
use GoBrave\Fogg\System\Response;
use GoBrave\Util\CaseConverter;
use GoBrave\Util\IWP;
use GoBrave\Util\Renderer;

class Router
{
    private $routes;
    private $config;
    private $wp;
    private $case_converter;
    private $renderer;

    private $route_part = '([A-Za-z0-9\-\_]+)';

    public function __construct(array $routes, Config $config, IWP $wp, CaseConverter $case_converter, Renderer $renderer)
    {
        $this->routes = $this->createRoutes($routes);
        $this->config = $config;
        $this->wp = $wp;
        $this->case_converter = $case_converter;
        $this->renderer = $renderer;
        $this->path = $wp->get_blog_details()->path;

        $this->setupRewrites($this->routes);
    }

    private function setupRewrites($routes)
    {
        global $wp_rewrite;

        $tags = $this->getRewriteTags($routes);

        foreach ($tags as $tag) {
            add_rewrite_tag('%_' . $tag . '%', '([^&]+)');
        }

        foreach ($routes as $route) {
            $rule = $route->getRewriteRule();
            $segments = $route->getSegments();
            $tags = $route->getTags();

            foreach ($segments as $key => $segment) {
                $segments[$key] = '_' . $segment . '=true';
            }

            foreach ($tags as $key => $tag) {
                $tags[$key] = '_' . $tag . '=$matches[' . ($key + 1) . ']';
            }

            $query = 'index.php?' . implode('&', $segments);
            $query = count($tags) ? $query . '&' . implode('&', $tags) : $query;

            if (is_array(get_option('rewrite_rules')) && !array_key_exists($rule, get_option('rewrite_rules'))) {
                $wp_rewrite->flush_rules(true);
            }

            add_rewrite_rule($rule, $query, 'top');
        }
    }

    private function createRoutes($routes)
    {
        $tmp = [];
        foreach ($routes as $uri => $options) {
            $tmp[] = new Route($uri, $options);
        }
        return $tmp;
    }

    private function getRewriteTags($routes)
    {
        $tags = array_merge($this->getTags($routes), $this->getSegments($routes));
        return array_unique($tags);
    }

    private function getTags($routes)
    {
        return $this->getPartsFromRoutes($routes, 'getTags');
    }

    private function getSegments($routes)
    {
        return $this->getPartsFromRoutes($routes, 'getSegments');
    }

    private function getPartsFromRoutes($routes, $method)
    {
        $parts = [];
        foreach ($routes as $route) {
            $parts = array_merge($parts, $route->{$method}());
        }

        return array_unique($parts);
    }

    public function route($vars)
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        if (strpos($uri, '#') !== false) {
            $uri = substr($uri, 0, strpos($uri, '#'));
        }
        if (strpos($uri, $this->path) !== false) {
            $uri = str_replace(rtrim($this->path, '/'), '', $uri);
        }

        $route = null;
        foreach ($this->routes as $tmp) {
            if ($tmp->match($uri, $vars, 'GET')) {
                $route = $tmp;
                break;
            }
        }

        if (!$route) {
            return;
        }

        $controller = $this->config->baseNamespace() . '\\Controllers\\' . $route->getController();
        $method = $route->getMethod();
        $vars = $route->getRouteVars($vars);
        $controller = new $controller($this->renderer);

        $controller->runBeforeFilters($this->case_converter->snakeToCamel($method, true));
        if (!method_exists($controller, $method)) {
            throw new \Exception('Method \'' . $method . '\' does not exist on ' . get_class($controller));
        }
        $response = $controller->{$method}($vars);
        $controller->runAfterFilters($this->case_converter->snakeToCamel($method, true));

        $response = $response ?: new Response();

        if ($response->type() === Response::JSON) {
            echo json_encode($response->vars(), JSON_PRETTY_PRINT);
            exit;
        }

        $res = new \stdClass();
        $res->vars = $response->vars();
        $res->template = implode('/', [
            'fogg',
            str_replace('_controller', '', $this->case_converter->camelToSnake($route->getController())),
            $this->case_converter->camelToSnake($route->getMethod()),
        ]);

        $this->wp->add_filter('body_class', function ($classes) use ($route) {
            $classes[] = str_replace('_', '-', 'fogg-' . $this->case_converter->camelToSnake($route->getController()));
            $classes[] = str_replace('_', '-', 'fogg-' . $this->case_converter->camelToSnake($route->getMethod()));
            return $classes;
        });

        return $res;
    }

    public function namedRoute($name, array $params)
    {
        $route = null;
        $uri = $_SERVER['REQUEST_URI'];
        foreach ($this->routes as $tmp) {
            if ($tmp->getName() === $name) {
                $route = $tmp;
            }
        }

        if (!$route) {
            return false;
        }
        if (strpos($uri, $this->path) !== false) {
            return $route->getPath($params, trim($this->path, '/'));
        }
        return $route->getPath($params, '');
    }
}
