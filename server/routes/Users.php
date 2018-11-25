<?php
  require_once "service/Users.php";
  require_once "core/Util.php";
  require_once "core/JWT.php";

  Router::get('/users/check', function ($req){
    if (Util::isKeyExists('name', $req)){
      return UserService::CheckUsernameExist($req['name']);
    }
    return true;
  });

  Router::post('/users/add', function ($req){
    if (Util::isKeyExists('username', $req) && Util::isKeyExists('password', $req) && 
        Util::isKeyExists('phone', $req) && Util::isKeyExists('address', $req) && Util::isKeyExists('name', $req)){
        //print_r($req);
        return UserService::Add($req);
    }

    return false;
  });

  Router::post('/users/login', function ($req){
    if (Util::isKeyExists('username', $req) && Util::isKeyExists('password', $req)) {
      return UserService::Login($req);
    }
    return false;
  });
?>