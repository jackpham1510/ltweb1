<?php 
  class BranchService{

    static function getAll(){
      $sql = SqlBuilder::from('branch')
        ->select()
        ->build();

      return Provider::select($sql);
    }

    static function getAllPaginate($page){
      $sql = SqlBuilder::from('branch')
        ->select();
      
      return Provider::paginate($sql, $page, 20);
    }

    static function getBy($by, $type, $value) {
      $sql = SqlBuilder::from('branch')
        ->where("$by = ?")
        ->select()
        ->build();
      
      $rs = Provider::select($sql, $type, $value);
      return is_null($rs) ? null : $rs[0];
    }

    static function getById($id){
      return Self::getBy('branch_id', 'i', [$id]);
    }

    static function getByName($name){
      return Self::getBy('name', 's', [$name]);
    }

    static function getByUrl($url){
      return Self::getBy('url', 's', [$url]);
    }
  }
?>