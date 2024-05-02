<?php

$router = new Router();

$router->get('/', function () {
  echo json_encode(['Project' => 'API SQLite']);
});

$router->get('/users', function () {
  $sql = new Sql(DB_FILE);
  $users = $sql->list('users');
  echo json_encode($users);
});

$router->get('/users/:id', function ($id) {
  $sql = new Sql(DB_FILE);
  $user = $sql->find('users', $id);
  echo json_encode($user);
});

$router->post('/users', function () {
  $formData = $_POST;
  $validationRules = [
    'name' => 'required|min_length:3|max_length:50',
    'password' => 'required|is_numeric',
  ];
  $errors = validateData($formData, $validationRules);
  if (!empty($errors)) {
    foreach ($errors as $error) {
      echo $error . "<br>";
    }
  } else {
    $sql = new Sql(DB_FILE);
    $newUserId = $sql->create('users', $formData);
    $user = $sql->find('users', $newUserId);
    echo json_encode($user);
  }
});

$router->put('/users/:id', function ($id) {
  $sql = new Sql(DB_FILE);
  $sql->update('users', $id, ['name' => 'John doe']);
});

$router->delete('/users/:id', function ($id) {
  $sql = new Sql(DB_FILE);
  $sql->delete('users', $id);
});

$router->resolve();
