<?php 
  require_once "../server/index.php";
  require_once "./Authen.php";

  session_start();

  if (!empty($_GET) && isset($_GET['url'])){
    $url = $_GET['url'];
    $isOk = CategoryService::delete($url) && unlink(Config::getValue('client_images')."/icon/$url.png");
    $_SESSION['rs_message'] = CreateRsMessage($isOk, "Delete category $url ". ($isOk ? 'success' : 'fail'));
    Redirect($_SESSION['last_url']);
  }
?>