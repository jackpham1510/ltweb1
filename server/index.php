<?php
  require_once "provider/Provider.php";
  require_once "core/Router.php";

  require_once "routes/Product.php";
  require_once "routes/Category.php";
  require_once "routes/Branch.php";

  header("Access-Control-Allow-Origin: *");
  Router::resolve();
?>