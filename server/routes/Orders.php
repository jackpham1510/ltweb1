<?php
  require_once "service/Orders.php";
  require_once "core/Util.php";

  Router::post('/orders/insert', function ($req) {

    if (Util::isKeyExists('order', $req)){
      return OrderService::insert($req['order']);
    }

    return false;
  });

  Router::get('/orders/list', function ($req) {
    if (Util::isKeyExists('page', $req) && Util::isKeyExists('username', $req)){
      return OrderService::getList($req['username'], $req['page']);
    } 
    return null;
  });

  Router::get('/orders/detail', function ($req) {
    if (Util::isKeyExists('order_id', $req)){
      return OrderService::getOrderDetail($req['order_id']);
    } 
    return null;
  });
?>