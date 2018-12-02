<?php 
  Router::get('/category/all', function (){
    return CategoryService::getAll();
  });
?>