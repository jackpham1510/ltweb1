<?php 
  require_once "core/Config.php";

  class Provider{
    private static $db;
    
    static function connect($config){
      self::$db = new mysqli(
        $config['servername'], 
        $config['username'], 
        $config['password'],
        $config['dbname']
      );

      mysqli_set_charset(self::$db, "utf8");

      if (self::$db->connect_error){
        die("Connection failed: ". $connection->connect_error);
      }
    }

    static function close(){
      if (!is_null(self::$db)){
        self::$db->close();
      }
    }

    static function select(string $sql){
      $qresult = self::$db->query($sql);
      $result = [];
      while ($row = $qresult->fetch_assoc()){
        foreach($row as $col => $value){
          $result[$col] = $value;
        }
      }
      return $result;
    }

    static function query(string $sql){
      return $db->query($sql);
    }
  }

  Provider::connect(Config::getDbConnection());
?>