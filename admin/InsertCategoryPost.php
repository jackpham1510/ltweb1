<?php
  require_once "./Helper.php";

  session_start();

  if (!empty($_POST)){
    $valid = isset($_POST['name']) && isset($_POST['url']);
    if ($valid){
      $errors = [];
      $_POST['name'] === '' ? array_push($errors, 'Name is required') : null;
      $_POST['url'] === '' ? array_push($errors, 'URL is required') : null;
      if (empty($errors)){

      }
      else {
        $_SESSION['insert_category_errors'] = $errors;
        Redirect($_SESSION['last_url']);
      }
    }
  }
?> 