<?php 
  class Util{
    static function jsonEncode($data){
      return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    static function error($errCode, $message){
      $json = [];
      $json["error"] = [];
      $json["error"]["code"] = $errCode;
      $json["error"]["message"] = $message;

      return $json;
    }

    static function isKeyExists($key, $data){
      return array_key_exists($key, $data) && isset($data[$key]); 
    }
  }
?>