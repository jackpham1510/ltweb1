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
      $page = intval($req["page"]);
      $ipp = $item_per_page;
      $isBranchExists = Util::isKeyExists('branch', $req);
      $isCategoryExists = Util::isKeyExists('category', $req);

      if (Util::isKeyExists('ipp', $req)){
        $ipp = intval($req['ipp']);
      }

      if ($isBranchExists && $isCategoryExists){
        return ProductService::getByCategoryAndBranch($req['category'], $req['branch'], $page, $ipp);
      }
      if ($isBranchExists){
        return ProductService::getByBranch($req['branch'], $page, $ipp);
      }
      if ($isCategoryExists){
        return ProductService::getByCategory($req['category'], $page, $ipp);
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

  Router::get('/product/one', function ($req){
    if (Util::isKeyExists('url', $req)){
      $url = $req['url'];
      ProductService::incView($url);
      return ProductService::getByUrl($url);
    }
  });
?>