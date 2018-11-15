<?php 
  require_once "service/Category.php";
  require_once "core/Router.php";

  Router::get('/category/all', function (){
    return CategoryService::getAll();
  });
?>