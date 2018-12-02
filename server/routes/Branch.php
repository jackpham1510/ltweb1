<?php 
  Router::get('/branch/all', function (){
    return BranchService::getAll(); 
  });
?>