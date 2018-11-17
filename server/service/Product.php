<?php 
  require_once "provider/Provider.php";
  require_once "core/Config.php";
  require_once "core/Util.php";

  class ProductService{

    private static $ITEM_PER_PAGE;

    static function init(){
      self::$ITEM_PER_PAGE = Config::getValue("product")["itemPerPage"];
    }

    static function getAll(int $page = 1){
      $sql = SqlBuilder::from('product')
        ->select();

      return Provider::paginate('product_id', $page, self::$ITEM_PER_PAGE, $sql);
    }

    static function top(string $type, int $top = 10){
      $sql = SqlBuilder::from('product')
        ->where('price is not null and quantity > 0')
        ->order("$type asc")
        ->limit($top)
        ->select()
        ->build();

      return Provider::select($sql);
    }

    static function getBy(string $from, string $where, int $page = 1){
      $sql = SqlBuilder::from($from)
        ->where($where)
        ->select('p.*');
      
      //echo $sql->build();

      return Provider::paginate('product_id', $page, self::$ITEM_PER_PAGE, $sql);
    }

    static function getByBranch(string $url, int $page = 1){
      return self::getBy('product p join branch b on p.branch_id=b.branch_id', "b.url = '$url'", $page);
    }
    
    static function getByCategory(string $url, int $page = 1){
      return self::getBy('product p join category c on p.category_id=c.category_id', "c.url = '$url'", $page);
    }

    static function getByCategoryAndBranch(string $category, string $branch, int $page = 1){
      return self::getBy(
        'product p join category c on p.category_id=c.category_id 
                   join branch b on b.branch_id=p.branch_id', 
        "c.url = '$category' and b.url = '$branch'", $page);
    }
  }

  ProductService::init();
?>