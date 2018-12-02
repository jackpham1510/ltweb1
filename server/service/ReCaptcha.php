<?php 
  class ReCaptchaService {
    static function verify($gres){
      $url = Config::getValue("grecaptcha-url");
      $secret = Config::getValue("grecaptcha-secret");
      $data = [
        "response" => $gres,
        "secret" => $secret];

      $options = [
        "http" => [
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query($data)
        ]];
      
      $context  = stream_context_create($options);
      $result = file_get_contents($url, false, $context);

      if (!$result){
        return false;
      }

      $verifyRes = json_decode($result, JSON_UNESCAPED_UNICODE);
      if (!$verifyRes["success"]){
        return false;
      }

      //print_r($result);

      return true;
    }
  }
?>