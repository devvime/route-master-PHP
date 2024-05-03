<?php

$router = new Router();

$router->get('/', function () {
  echo json_encode(['Project' => 'API SQLite']);
});

require_once(__DIR__.'/functions/user.php');

$router->resolve();
