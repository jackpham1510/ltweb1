<?php
  require_once "../server/index.php";
  require_once "./Authen.php";

  if (!empty($_POST)){
    $valid = isset($_POST['id']) && isset($_POST['name']) && isset($_POST['url']);
    if ($valid){
      $errors = [];
      $id = $_POST['id'];
      $name = $_POST['name'];
      $url = MakeUrl($_POST['url']);
      $oldUrl = $_POST['oldUrl'];

      $name === '' ? array_push($errors, 'Name is required') : null;
      $_POST['url'] === '' ? array_push($errors, 'URL is required') : null;
      $byName = BranchService::getByName($name);
      $byUrl = BranchService::getByUrl($url);
      isset($byName) && $byName['BRANCH_ID'] != $id ? array_push($errors, 'Name is existed') : null;
      isset($byUrl) && $byUrl['BRANCH_ID'] != $id ? array_push($errors, 'URL is existed') : null;

      var_dump($errors);
      $isOk = empty($errors);
      $hasLogo = $isOk && is_file_ok($_FILES['logo']);
      $file = $hasLogo ? $_FILES['logo'] : null;
      $removeOldLogoSuccess = !$hasLogo || unlink(Config::getValue('client_images')."/logo/$oldUrl.jpg");
      $saveImgSuccess = !$hasLogo || (FilterFileType($file, ['jpg']) && SaveFile($file, Config::getValue('client_images').'/logo', $url, '.jpg'));
      $isOk = $saveImgSuccess && BranchService::update($_POST['id'], $name, $_POST['url']);

      if (!$isOk) {
        array_push($errors, 'Update failed, please try again!');
        $_SESSION['update_brand_errors'] = $errors;
        Redirect($_SESSION['last_url']);
      }
      else {
        $_SESSION['rs_message'] = CreateRsMessage(true, "Update brand '$name' success");
        Redirect('./Brands.php');
      }
    }
  }
?> 