<?php
  class Router{
    private static $GET = [];
    private static $POST = [];

    static function get($path, $func){
      self::$GET[$path] = $func; 
    }

    static function post($path, $func){
      self::$POST[$path] = $func;
    }

    static function formatRoute($path){
      $path = str_replace(Config::getValue('dirname'), "", $path);
      $path = $path === "" ? "/" : $path;
      $pos = strpos($path, "?");
      if ($pos){
        $path = substr($path, 0, $pos);
      }
      return $path;
    }

    static function callFunc($method, $path, $request){
      if (array_key_exists($path, $method)){
        return $method[$path]($request);
      }
      
      return Util::error(1, "Unhandled Request");
    }

    static function resolve(){
      $requestMethod = $_SERVER["REQUEST_METHOD"];
      $path = self::formatRoute($_SERVER["REQUEST_URI"]);

      //print_r($_POST);

      switch ($requestMethod){
        case "GET":
          echo Util::jsonEncode(self::callFunc(self::$GET, $path, $_GET));
          break;
        case "POST":
          // For Content-type: JSON
          $rest_json = file_get_contents("php://input");
          $_POST = json_decode($rest_json, true);

          echo Util::jsonEncode(self::callFunc(self::$POST, $path, $_POST));
          break;
        default:
          echo Util::jsonEncode(Util::error(-1, "Unhandled HTTP Method"));
      }
    }
  }
?>