<?php 
  require_once "../server/index.php";
  require_once "./Authen.php";

  session_start();

  if (!empty($_POST) && isset($_POST['id'])){
    $id = intval($_POST['id']);
    $isOk = OrderService::updateStatus($id, intval($_POST['status']));
    $_SESSION['rs_message'] = CreateRsMessage($isOk, "Update order $id ". ($isOk ? 'success' : 'fail'));
    Redirect($_SESSION['last_url']);
  }
?>