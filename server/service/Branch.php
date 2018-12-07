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

    static function insert($name, $url){
      return Provider::transaction(function () use ($name, $url){
        $sql = SqlBuilder::from('branch(name, url)')
          ->insert('?, ?')
          ->build();

        return Provider::query($sql, 'ss', [$name, $url], true);
      });
    }

    static function update($id, $name, $url){
      return Provider::transaction(function () use ($id, $name, $url){
        $sql = SqlBuilder::from('branch')
          ->update('name = ?, url = ?')
          ->where('branch_id = ?')
          ->build();

        return Provider::query($sql, 'ssi', [$name, $url, $id], true);
      });
    }

    static function delete($url) {
      return Provider::transaction(function () use ($url){
        $sql = SqlBuilder::from('branch')
          ->where('url = ?')
          ->delete()
          ->build();

        return Provider::query($sql, 's', [$url], true);
      });
    }
  }
?>