<?php 
  require_once "provider/Provider.php";

  class ProductService{

    static function getAll(){
      $sql = 
      "select * from 
       PRODUCT limit 20";
      
      return Provider::select($sql);
    }

    static function topSold(int $top){
      $sql = 
        "select * 
         from PRODUCT
         order by SOLD desc
         limit $top";
      
      return Provider::select($sql);
    }

    static function topView(int $top){
      $sql = 
        "select *
         from PRODUCT
         order by VIEW desc
         limit $top";
      
      return Provider::select($sql);
    }

    static function getByBranch(string $branch){
      $sql = 
        "select *
         from PRODUCT p join BRANCH b
         on p.BRANCH_ID = b.BRANCH_ID
         and b.NAME = $branch";
      
      return Provider::paginate($sql);
    }
  }
?>