<?php
  require_once "../server/index.php";
  require_once "./Authen.php";

  if (!empty($_POST)){
    $valid = isset($_POST['name']) && isset($_POST['url']);
    if ($valid){
      $errors = [];
      $name = $_POST['name'];
      $url = MakeUrl($_POST['url']);

      $name === '' ? array_push($errors, 'Name is required') : null;
      $url === '' ? array_push($errors, 'URL is required') : null;
      !is_file_ok($_FILES['logo']) ? array_push($errors, 'Logo is required') : null;
      !is_null(BranchService::getByName($name)) ? array_push($errors, 'Name is existed') : null;
      !is_null(BranchService::getByUrl($url)) ? array_push($errors, 'URL is existed') : null;

      $file = $_FILES['logo'];
      $isOk = empty($errors) && FilterFileType($file, ['jpg']) 
        && SaveFile($file, Config::getValue('client_images').'/logo', $url, '.jpg')
        && BranchService::insert($name, $url);

      if (!$isOk) {
        array_push($errors, 'Insert failed, please try again!');
        $_SESSION['insert_brand_error'] = $errors;
        Redirect($_SESSION['last_url']);
      }
      else {
        $_SESSION['rs_message'] = CreateRsMessage(true, "Insert brand '$name' success");
        Redirect('./Brands.php');
      }
    }
  }
?> 