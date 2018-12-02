<?php 
  require_once "SqlBuilder.php";

  class Provider{
    private static $db;
    
    static function connect($config){
      self::$db = new mysqli(
        $config['servername'], 
        $config['username'], 
        $config['password'],
        $config['dbname']
      );

      self::$db->set_charset('utf8mb4');

      //self::$db->real_escape_string();

      if (self::$db->connect_error){
        die("Connection failed: ". $connection->connect_error);
      }
    }

    static function close(){
      if (!is_null(self::$db)){
        self::$db->close();
      }
    }

    static function query(string $sql, string $types = "", $params = [], bool $modify = false){
      $q = self::$db->prepare($sql);
      //print_r($sql);
      //echo "\n";
      //print_r($params);

      if ($q){
        if ($types != ""){
          $q->bind_param($types, ...$params);
        }
        
        $rs = $q->execute();
        
        if ($modify){
          return $rs;
        }

        return $q->get_result();
      }

      return null;
    }

    static function insertAndGetLastID(string $sql, string $types, $params){
      if (self::query($sql, $types, $params, true)){
        //print_r(self::$db->insert_id);
        return self::$db->insert_id;
      }
      return false;
    }

    static function select(string $sql, string $types = "", $params = []){
      $qresult = self::query($sql, $types, $params);
      //print_r($qresult);

      if ($qresult && $qresult->num_rows > 0){
        $result = [];
        while ($row = $qresult->fetch_assoc()){
          array_push($result, $row);
        }
        return $result;
      }
      return null;
    }

    static function paginate(SqlBuilder $sqlBuilder, int $page, int $itemPerPage, string $types = "", $params = []){
      $result = [];

      $sqlClone = clone $sqlBuilder;
      $countSql = $sqlClone->select("count(*) as total")->build();
      $countResult = self::query($countSql, $types, $params);
      //print_r($countResult);
      //print_r($sqlBuilder);

      if ($countResult){
        $result["total"] = $countResult->fetch_assoc()["total"];
        $result["totalPages"] = ceil($result["total"] / $itemPerPage);
        $result["active"] = $page;
  
        $sql = $sqlBuilder->paginate($page, $itemPerPage)->build();
        //print_r($sql);
  
        $result["data"] = self::select($sql, $types, $params);
  
        return $result;
      }
      
      return null;
    }

    static function transaction($method) {
      self::$db->autocommit(false);

      $isOk = $method();

      if ($isOk) {
        self::$db->commit();
      }
      else {
        self::$db->rollback();
      }

      self::$db->autocommit(true);

      return $isOk;
    }
  }

  Provider::connect(Config::getDbConnection());
?>