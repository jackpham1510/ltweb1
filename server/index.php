<?php
  require_once "core/Config.php";
  require_once "core/Util.php";
  require_once "core/JWT.php";
  require_once "core/Router.php";

  require_once "provider/Provider.php";

  require_once "service/Banner.php";
  require_once "service/Branch.php";
  require_once "service/Category.php";
  require_once "service/Orders.php";
  require_once "service/Product.php";
  require_once "service/ReCaptcha.php";
  require_once "service/Users.php";

  if (strpos($_SERVER["REQUEST_URI"], '/admin') === false){
    require_once "routes/Product.php";
    require_once "routes/Category.php";
    require_once "routes/Branch.php";
    require_once "routes/Banner.php";
    require_once "routes/Users.php";
    require_once "routes/Orders.php";
    require_once "routes/ReCaptcha.php";
  
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: GET, POST');
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept");
  
    Router::resolve();
  }
?>