<?php 
  Router::get('/grecaptcha/verify', function ($req){
    if (Util::isKeyExists("gres", $req)){
      return ReCaptchaService::verify($req["gres"]);
    }

    return false;
  });
?>