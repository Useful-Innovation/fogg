<?php namespace GoBrave\Fogg\Front;

class Route
{
  private $uri;
  private $tags;
  private $options;

  const TAG_REWRITE = '([A-Za-z0-9-]+)';

  public function __construct($uri, array $options) {
    $this->uri     = $uri;
    $this->tags    = $this->parseRewriteTags($uri);
    $this->options = $options;
  }

  //
  //    Prep methods
  //
  public function getRewriteRule() {
    $uri = $this->uri;
    if(substr($uri, 0, 1) === '/') {
      $uri = substr($uri, 1);
    }
    $uri = explode('/', $uri);

    $rewrite = [];
    foreach($uri as $segment) {
      if($tmp = $this->uriSegmentIsTag($segment, $this->tags)) {
        $rewrite[] = self::TAG_REWRITE;
      } else {
        $rewrite[] = t('fogg.uri.' . $segment);
      }
    }

    return '^' . implode('/', $rewrite) .'/?$';
  }

  public function getSegments() {
    $uri = $this->uri;
    if(substr($uri, 0, 1) === '/') {
      $uri = substr($uri, 1);
    }
    $uri = explode('/', $uri);
    $segments = [];
    foreach($uri as $segment) {
      if(!$this->uriSegmentIsTag($segment, $this->tags)) {
        $segments[] = $segment;
      }
    }

    return $segments;
  }

  public function getTags() {
    return $this->tags;
  }

  public function getPath($params) {
    $parts = explode('/', trim($this->uri, '/'));
    foreach($parts as $key => $part) {
      if($this->uriSegmentIsTag($part, $this->getTags())) {
        $parts[$key] = array_shift($params);
      } else {
        $parts[$key] = t('fogg.uri.' . $part);
      }
    }
    return '/' . implode('/', $parts);
  }





  //
  //      Route methods
  //
  public function match($uri, $vars, $type) {

    if($this->options['type'] !== $type) {
      return false;
    }

    $rewrite = $this->getRewriteRule();
    $rewrite = str_replace('/', '\/', $rewrite);
    $rewrite = '/' . $rewrite . '/';
    $uri     = ltrim($uri, '/');

    $matches = null;

    preg_match($rewrite, $uri, $matches);

    return (bool)$matches;
  }

  public function getController() {
    return $this->options['controller'];
  }

  public function getMethod() {
    return $this->options['method'];
  }

  public function getName() {
    return $this->options['name'];
  }

  public function getRouteVars($vars) {
    $tags = [];
    foreach($vars as $key => $value) {
      if($value != 'true') {
        if(strpos($key, '_') === 0) {
          $key = substr($key, 1);
        }
        $tags[$key] = $value;
      }
    }
    return $tags;
  }



  //
  //
  //    Helpers
  //
  //
  private function parseRewriteTags($uri) {
    $tags    = [];
    $matches = [];
    preg_match_all("/{[a-z\_]*}/", $uri, $matches);
    $matches = $matches[0];
    foreach($matches as $match) {
      $match  = str_replace('{', '', $match);
      $match  = str_replace('}', '', $match);
      $tags[] = $match;
    }

    return array_unique($tags);
  }

  private function uriSegmentIsTag($segment, $tags) {
    $segment = str_replace('{', '', $segment);
    $segment = str_replace('}', '', $segment);
    if(in_array($segment, $tags)) {
      return $segment;
    }
    return false;
  }
}
