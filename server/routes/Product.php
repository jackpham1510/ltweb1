<?php
  require_once "service/Product.php";

  Router::get('/product/all', function ($req){
    if (is_null($req["p"])) return null;
    
    $page = intval($req["p"]);
    
    return ProductService::getAll($page);
  });

  Router::get('/product/by/branch', function ($req){
    if (is_null($req["b"]) || is_null($req["p"])) return null;
    
    $branch_id = intval($req["b"]);
    $page = intval($req["p"]);

    return ProductService::getByBranch($branch_id, $page);
  });

  Router::get('/product/by/category', function ($req){
    if (is_null($req["c"]) || is_null($req["p"])) return null;

    $category_id = intval($req["c"]);
    $page = intval($req["p"]);

    return ProductService::getByCategory($category_id, $page);
  });

  Router::get('/product/top/view', function ($req){
    $top = $req["t"];
    if (isset($top))
      return ProductService::topView(intval($top));

    return ProductService::topView();
  });

  Router::get('/product/top/sold', function ($req){
    $top = $req["t"];
    if (isset($top))
      return ProductService::topSold(intval($top));

    return ProductService::topSold();
  });


?>