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
      $byName = CategoryService::getByName($name);
      $byUrl = CategoryService::getByUrl($url);
      isset($byName) && $byName['CATEGORY_ID'] != $id ? array_push($errors, 'Name is existed') : null;
      isset($byUrl) && $byUrl['CATEGORY_ID'] != $id ? array_push($errors, 'URL is existed') : null;

      var_dump($errors);
      $isOk = empty($errors);
      $hasIcon = $isOk && is_file_ok($_FILES['icon']);
      $file = $hasIcon ? $_FILES['icon'] : null;
      $removeOldiconSuccess = !$hasIcon || unlink(Config::getValue('client_images')."/icon/$oldUrl.png");
      $saveImgSuccess = !$hasIcon || (FilterFileType($file, ['png']) && SaveFile($file, Config::getValue('client_images').'/icon', $url.'-32', '.png'));
      $isOk = $saveImgSuccess && CategoryService::update($_POST['id'], $name, $_POST['url']);

      if (!$isOk) {
        array_push($errors, 'Update failed, please try again!');
        $_SESSION['update_category_errors'] = $errors;
        Redirect($_SESSION['last_url']);
      }
      else {
        $_SESSION['rs_message'] = CreateRsMessage(true, "Update category '$name' success");
        Redirect('./Categories.php');
      }
    }
  }
?> 