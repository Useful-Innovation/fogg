<?php

define('BASE_PATH', realpath(__DIR__ . '/..'));

$config = new GoBrave\Fogg\Config([
  'routes'            => 'routes',
  'base_namespace'    => 'App',
  'admin_page_name'   => 'admin_page_name',
  'controller_suffix' => 'Controller',
  'root_path'         => BASE_PATH . '/tests/root',
  'generator'         => [
    'model_extend_from' => [
      'namespace' => 'App\\Api\\System',
      'class'     => 'Model'
    ],
    'controller_extend_from' => [
      'admin' => [
        'namespace' => 'App\\Api\\System',
        'class'     => 'AdminController'
      ],
      'front' => [
        'namespace' => 'App\\Api\\System',
        'class'     => 'FrontController'
      ]
    ]
  ]
]);
