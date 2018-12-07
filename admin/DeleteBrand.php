<?php 
  require_once "../server/index.php";
  require_once "./Authen.php";

  session_start();

  if (!empty($_GET) && isset($_GET['url'])){
    $url = $_GET['url'];
    $isOk = BranchService::delete($url) && unlink(Config::getValue('client_images')."/logo/$url.jpg");
    $_SESSION['rs_message'] = CreateRsMessage($isOk, "Delete brand $url ". ($isOk ? 'success' : 'fail'));
    Redirect($_SESSION['last_url']);
  }
?>