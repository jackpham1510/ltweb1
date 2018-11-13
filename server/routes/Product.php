<?php 
  require_once "service/Product.php";

  Router::get('/product/all', function (){
    echo json_encode(ProductService::all(), JSON_UNESCAPED_UNICODE);
  });
?>