<?php 
  require_once "provider/Provider.php";

  class BranchService{

    static function getAll(){
      $sql = SqlBuilder::from('branch')
        ->select()
        ->build();

      return Provider::select($sql);
    }
  }
?>