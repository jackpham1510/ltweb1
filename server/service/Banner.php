<?php 
  require_once "provider/Provider.php";

  class BannerService{

    static function getAll(){
      $sql = SqlBuilder::from('banner')
        ->where('type != 0')
        ->order('type asc')
        ->select()
        ->build();

      return Provider::select($sql);
    }
  }
?>