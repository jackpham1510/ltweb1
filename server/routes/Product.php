<?php
  require_once "service/Product.php";
  require_once "core/Util.php";
  require_once "core/Config.php";

  $item_per_page = Config::getValue('product')['itemPerPage'];

  Router::get('/product/all', function ($req){
    if (!Util::isKeyExists('page', $req)) return null;
    
    $page = intval($req["page"]);
    
    return ProductService::getAll($page);
  });

  Router::get('/product/by', function ($req) use ($item_per_page){
    if (Util::isKeyExists('page', $req)){
      return ProductService::getList($req, $item_per_page);
    } 
    return null;
  });

  Router::get('/product/search', function ($req) use ($item_per_page){
    if (Util::isKeyExists('input', $req) && Util::isKeyExists('page', $req)){
      $page = intval($req['page']);
      $input = $req['input'];
      return ProductService::search($input, $page, $item_per_page);
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

  Router::get('/product/one', function ($req){
    if (Util::isKeyExists('url', $req)){
      $url = $req['url'];
      ProductService::incView($url);
      return ProductService::getByUrl($url);
    }

    return null;
  });

  Router::get('/product/in', function ($req){
    if (Util::isKeyExists('idlist', $req)){
      return ProductService::getInIdList($req['idlist']);
    }

    return null;
  });
?>