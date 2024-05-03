<?php

function validateData($data) {
  $errors = [];

  foreach ($data as $key => $value) {
      switch ($key) {
          case 'email':
              if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                  $errors[$key] = 'O e-mail inserido é inválido.';
              }
              break;
          case 'required':
              if (empty($value)) {
                  $errors[$key] = 'Este campo é obrigatório.';
              }
              break;
          case 'min_length':
              $min_length = intval($value);
              if (strlen($data['value']) < $min_length) {
                  $errors[$key] = "Este campo deve ter no mínimo {$min_length} caracteres.";
              }
              break;
          case 'max_length':
              $max_length = intval($value);
              if (strlen($data['value']) > $max_length) {
                  $errors[$key] = "Este campo deve ter no máximo {$max_length} caracteres.";
              }
              break;
          case 'is_date':
              if (!strtotime($value)) {
                  $errors[$key] = 'Este não é um formato de data válido.';
              }
              break;
          case 'is_numeric':
              if (!is_numeric($value)) {
                  $errors[$key] = 'Este campo deve ser um número.';
              }
              break;
          // Adicione mais casos para outras validações conforme necessário
      }
  }

  return $errors;
}
