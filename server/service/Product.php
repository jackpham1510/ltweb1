<?php 
  require_once "provider/Provider.php";

  class ProductService{
   
    static function all(){
      $sql = "select * from PRODUCT limit 20";
      return Provider::select($sql);
    } 
  }
?>