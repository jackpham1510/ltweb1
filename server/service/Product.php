<?php 
  require_once "provider/Provider.php";
  require_once "core/Config.php";

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

    static function topNew(int $top = 10){
      $sql = SqlBuilder::from('product')
        ->where('price is not null and quantity > 0')
        ->order('product_id desc')
        ->limit($top)
        ->select()
        ->build();
      
      return Provider::select($sql);
    }

    static function topSold(int $top = 10){
      $sql = SqlBuilder::from('product')
        ->where('price is not null and quantity > 0')
        ->order('sold desc')
        ->limit($top)
        ->select()
        ->build();
      
      return Provider::select($sql);
    }

    static function topView(int $top = 10){
      $sql = SqlBuilder::from('product')
        ->where('price is not null and quantity > 0')
        ->order('view desc')
        ->limit($top)
        ->select()
        ->build();
      
      return Provider::select($sql);
    }

    static function getByBranch(string $branch_id, int $page = 1){
      $sql = SqlBuilder::from('product')
        ->where("branch_id = $branch_id and quantity > 0")
        ->select();
      
      return Provider::paginate('product_id', $page, self::$ITEM_PER_PAGE, $sql);
    }

    static function getByCategory(string $cate_id, int $page = 1){
      $sql = SqlBuilder::from('product')
        ->where("category_id = $category_id and quantity > 0")
        ->select();
      
      return Provider::paginate('product_id', $page, self::$ITEM_PER_PAGE, $sql);
    }
  }

  ProductService::init();
?>