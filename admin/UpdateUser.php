<?php 
  require_once "../server/index.php";
  require_once "./Authen.php";

  session_start();

  if (!empty($_POST) && isset($_POST['username'])){
    $username = $_POST['username'];
    $isOk = UserService::updateType($_POST['username'], intval($_POST['type']));
    $_SESSION['rs_message'] = CreateRsMessage($isOk, "Update user $username ". ($isOk ? 'success' : 'fail'));
    Redirect($_SESSION['last_url']);
  }
?>