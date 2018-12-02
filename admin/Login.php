<?php
  require_once "../server/index.php";
  require_once "Helper.php";

  session_start();

  $success = false;

  if (!empty( $_POST )) {
    if (isset( $_POST['username'] ) && isset( $_POST['password'])) {
      $userInfo = UserService::GetUserInfo($_POST['username']);

      if ($userInfo != null && password_verify($_POST['password'], $userInfo['PASSWORD']) && $userInfo["TYPE"] === 2){
        $_SESSION['username'] = $userInfo["USERNAME"];
        $success = true;
      }
    }
  }

  if ($success){
    Redirect('/Dashboard.php');
  }
  else {
    $_SESSION["login_error"] = true;
    Redirect("/");
  }
?>