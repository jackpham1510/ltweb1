<?php
  require_once "service/Product.php";
  require_once "core/Util.php";

  Router::get('/product/all', function ($req){
    if (!Util::isKeyExists('page', $req)) return null;
    
    $page = intval($req["page"]);
    
    return ProductService::getAll($page);
  });

  Router::get('/product/by', function ($req){
    if (Util::isKeyExists('page', $req)){
      $page = intval($req["page"]);
      $isBranchExists = Util::isKeyExists('branch', $req);
      $isCategoryExists = Util::isKeyExists('category', $req);

      if ($isBranchExists && $isCategoryExists){
        return ProductService::getByCategoryAndBranch($req['category'], $req['branch'], $page);
      }
      if ($isBranchExists){
        return ProductService::getByBranch($req['branch'], $page);
      }
      if ($isCategoryExists){
        return ProductService::getByCategory($req['category'], $page);
      }
    } 
    return null;
  });

  Router::get('/product/top', function ($req){
    $productTops = ["time_stamp", "view", "sold"];

    if (!Util::isKeyExists('type', $req) || !in_array($req['type'], $productTops)) return null;

    if (Util::isKeyExists('top', $req))
      return ProductService::top($req['type'], intval($req["top"]));

    return ProductService::top($req['type']);
  });
?>