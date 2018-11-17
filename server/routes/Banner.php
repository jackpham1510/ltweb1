<?php 
  require_once "service/Banner.php";
  require_once "core/Router.php";

  Router::get('/banner/all', function (){
    return BannerService::getAll(); 
  });
?>