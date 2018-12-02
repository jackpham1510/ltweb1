<?php 
  Router::get('/banner/all', function (){
    return BannerService::getAll(); 
  });
?>