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

    static function paginate(string $id, int $page, int $itemPerPage, SqlBuilder $sqlBuilder){
      $result = [];
      $table = $sqlBuilder->qfrom;
      $countSql = SqlBuilder::from($table)->select("count(*) as total")->build();
      $countResult = self::$db->query($countSql);
      
      $result["total"] = $countResult->fetch_assoc()["total"];
      $result["totalPages"] = ($countResult / $itemPerPage);
      $result["active"] = $page;

      $sql = $sqlBuilder->paginate($id, $page, $itemPerPage)->build();
      
      $result["data"] = self::select($sql);

      return $result;
    }
  }

  Provider::connect(Config::getDbConnection());
?>