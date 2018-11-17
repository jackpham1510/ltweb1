<?php
  require_once "service/Product.php";
  require_once "core/Util.php";

  Router::get('/product/all', function ($req){
    if (!Util::isKeyExists('p', $req)) return null;
    
    $page = intval($req["p"]);
    
    return ProductService::getAll($page);
  });

  Router::get('/product/by/branch', function ($req){
    if (!Util::isKeyExists('p', $req) || !Util::isKeyExists('b', $req)) return null;
    
    $branch_id = intval($req["b"]);
    $page = intval($req["p"]);

    return ProductService::getByBranch($branch_id, $page);
  });

  Router::get('/product/by/category', function ($req){
    if (!Util::isKeyExists('c', $req) || !Util::isKeyExists('p', $req)) return null;

    $category_id = intval($req["c"]);
    $page = intval($req["p"]);

    return ProductService::getByCategory($category_id, $page);
  });

  Router::get('/product/top/view', function ($req){
    if (Util::isKeyExists('t', $req))
      return ProductService::topView(intval($req["t"]));

    return ProductService::topView();
  });

  Router::get('/product/top/sold', function ($req){
    if (Util::isKeyExists('t', $req))
      return ProductService::topSold(intval($req["t"]));

    return ProductService::topSold();
  });

  Router::get('/product/top/new', function ($req){
    if (Util::isKeyExists('t', $req))
      return ProductService::topNew(intval($req["t"]));

    return ProductService::topNew();
  });
?>