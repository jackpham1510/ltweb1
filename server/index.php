<?php
  require_once "provider/Provider.php";
  require_once "core/Router.php";

  require_once "routes/Product.php";
  require_once "routes/Category.php";
  require_once "routes/Branch.php";
  require_once "routes/Banner.php";
  require_once "routes/Users.php";

  header("Access-Control-Allow-Origin: *");
  header('Access-Control-Allow-Methods: GET, POST');
  header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept");

  Router::resolve();
?>