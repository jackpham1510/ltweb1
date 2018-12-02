<?php
  require_once "./Helper.php";

  session_start();

  if (!empty($_POST)){
    $valid = isset($_POST['name']) && isset($_POST['url']) && isset($_POST['detail']) && isset($_POST['price']) && isset($_POST['quantity']);
    if ($valid){
      $errors = [];
      strlen($_POST['name']) < 8 ? array_push($errors, 'Name must be at least 8 characters') : null;
      $_POST['url'] === '' ? array_push($errors, 'URL is required') : null;
      $_POST['detail'] === '' ? array_push($errors, 'Detail is required') : null;
      intval($_POST['price']) < 1000 ? array_push($errors, 'Price invalid') : null;
      intval($_POST['quantity']) < 1 ? array_push($errors, 'Quantity invalid') : null;
      if (empty($errors)){

      }
      else {
        $_SESSION['insert_product_errors'] = $errors;
        Redirect($_SESSION['last_url']);
      }
    }
  }
?> 