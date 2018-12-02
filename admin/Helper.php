<?php 
  require_once "../server/index.php";

  function Redirect(string $path){
    header("Location: ".Config::getValue("admin_base").$path);
  }

  function GetPath() {
    return substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "/"));
  }
?>