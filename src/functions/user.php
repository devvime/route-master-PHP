<?php

$router->get('/users', function () {
  try {
    $sql = new Sql(DB_FILE);
    $users = $sql->list('users');
    echo json_encode($users);
  } catch (PDOException $e) {
    echo json_encode(["error"=>"SQLite connection error: " . $e->getMessage()]);
    exit();
  }
});

$router->get('/users/:id', function ($id) {
  try {
    $sql = new Sql(DB_FILE);
    $user = $sql->find('users', $id);
    echo json_encode($user);
  } catch (PDOException $e) {
    echo json_encode(["error"=>"SQLite connection error: " . $e->getMessage()]);
    exit();
  }
});

$router->post('/users', function () {
  $formData = $_POST;
  $errors = validateData($formData, [
    'name' => 'required|min_length:3|max_length:50',
    'password' => 'required|is_numeric',
  ]);
  if (!empty($errors)) {
    foreach ($errors as $error) {
      echo $error . "<br>";
    }
  } else {
    try {
      $formData['password'] = password_hash($formData['password'], PASSWORD_DEFAULT);
      $sql = new Sql(DB_FILE);
      $newUserId = $sql->create('users', $formData);
      $user = $sql->find('users', $newUserId);
      echo json_encode($user);
    } catch (PDOException $e) {
      echo json_encode(["error"=>"SQLite connection error: " . $e->getMessage()]);
      exit();
    }
  }
});

$router->put('/users/:id', function ($id) {
  try {
    $formData = $_POST;
    if ($formData['password']) {
      $formData['password'] = password_hash($formData['password'], PASSWORD_DEFAULT);
    }
    $sql = new Sql(DB_FILE);
    $sql->update('users', $id, $formData);
  } catch (PDOException $e) {
    echo json_encode(["error"=>"SQLite connection error: " . $e->getMessage()]);
    exit();
  }
});

$router->delete('/users/:id', function ($id) {
  try {
    $sql = new Sql(DB_FILE);
    $sql->delete('users', $id);
  } catch (PDOException $e) {
    echo json_encode(["error"=>"SQLite connection error: " . $e->getMessage()]);
    exit();
  }
});