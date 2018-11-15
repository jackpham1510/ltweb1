<?php 
  class CategoryService{
    
    static function getAll(){
      $sql = SqlBuilder::from('category')
        ->select()
        ->build();
      
      return Provider::select($sql);
    }
  }
?>