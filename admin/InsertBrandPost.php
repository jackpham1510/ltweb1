<?php
  require_once "../server/index.php";
  require_once "./Helper.php";

  session_start();

  if (!empty($_POST)){
    $valid = isset($_POST['name']) && isset($_POST['url']);
    if ($valid){
      $errors = [];
      $_POST['name'] === '' ? array_push($errors, 'Name is required') : null;
      $_POST['url'] === '' ? array_push($errors, 'URL is required') : null;
      !is_null(BranchService::getByName($_POST['name'])) ? array_push($errors, 'Name is existed') : null;
      !is_null(BranchService::getByUrl($_POST['url'])) ? array_push($errors, 'URL is existed') : null;

      //print_r($errors);

      if (empty($errors)){

      }
      else {
        $_SESSION['insert_brand_errors'] = $errors;
        Redirect($_SESSION['last_url']);
      }
    }
  }
?> 