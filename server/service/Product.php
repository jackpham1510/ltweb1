<?php 
  require_once "provider/Provider.php";
  require_once "core/Util.php";

  class ProductService{

    static function getAll(int $page = 1){
      $sql = SqlBuilder::from('product')
        ->select();

      return Provider::paginate('product_id', $page, 20, $sql);
    }

    static function top(string $type, int $top = 10){
      $sql = SqlBuilder::from('product')
        ->where('price is not null and quantity > 0')
        ->order("$type desc")
        ->limit($top)
        ->select()
        ->build();

      return Provider::select($sql);
    }

    static function getByUrl(string $url){
      $sql = SqlBuilder::from('product')
        ->where("url='$url'")
        ->select()
        ->build();
      
      return Provider::select($sql)[0];
    }

    static function getBy(string $from, string $where, int $page = 1, int $ipp = 20){
      $sql = SqlBuilder::from($from)
        ->where($where)
        ->select('p.*');

      return Provider::paginate('product_id', $page, $ipp, $sql);
    }

    static function getByBranch(string $url, int $page = 1, int $ipp = 20){
      return self::getBy('product p join branch b on p.branch_id=b.branch_id', "b.url = '$url'", $page, $ipp);
    }
    
    static function getByCategory(string $url, int $page = 1, int $ipp = 20){
      return self::getBy('product p join category c on p.category_id=c.category_id', "c.url = '$url'", $page, $ipp);
    }

    static function getByCategoryAndBranch(string $category, string $branch, int $page = 1, int $ipp = 20){
      return self::getBy(
        'product p join category c on p.category_id=c.category_id 
                   join branch b on b.branch_id=p.branch_id', 
        "c.url = '$category' and b.url = '$branch'", $page, $ipp);
    }

    static function incView(string $url){
      $sql = SqlBuilder::from('product')
        ->where("url='$url'")
        ->update('view=view+1')
        ->build();
      
      Provider::query($sql);
    }
  }
?>