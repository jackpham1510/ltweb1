<?php 
  require_once "service/Branch.php";
  require_once "core/Router.php";

  Router::get('/branch/all', function (){
    return BranchService::getAll(); 
  });
?>