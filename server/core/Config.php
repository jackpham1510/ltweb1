<?php 
  class Config{
    private static $config;

    static function init(){
      $jsonStr = file_get_contents('../config.json');

      if (isset($jsonStr)){
        self::$config = json_decode($jsonStr, true);
      }
      else {
        throw new Exception('Cannot open config.json');
      }
    }

    static function getValue(string $key){
      return self::$config[$key];
    }

    static function getDbConnection(){
      return self::getValue('db_connection');
    }
  }

  Config::init();
?>