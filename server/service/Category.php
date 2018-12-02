<?php 
  class CategoryService{
    
    static function getAll(){
      $sql = SqlBuilder::from('category')
        ->select()
        ->build();
      
      return Provider::select($sql);
    }

    static function getAllPaginate($page){
      $sql = SqlBuilder::from('category')
        ->select();
      
      return Provider::paginate($sql, $page, 20);
    }

    static function getBy($by, $type, $value) {
      $sql = SqlBuilder::from('category')
        ->where("$by = ?")
        ->select()
        ->build();
      
      $rs = Provider::select($sql, $type, $value);
      return !is_null($rs) ? $rs[0] : null;
    }

    static function getById($id){
      return Self::getBy('category_id', 'i', [$id]);
    }

    static function getByName($name){
      return Self::getBy('category_id', 'i', [$name]);
    }

    static function getByUrl($url){
      return Self::getBy('url', 's', [$url]);
    }
  }
?>