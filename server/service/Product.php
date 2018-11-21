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

    static function order(string $type, string $mode){
      $sql = SqlBuilder::from('product')
        ->where('quantity > 0')
        ->order("$type $mode")
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

    static function search(string $input, int $page, int $ipp){
      $input = preg_replace('/[\W_]+/', "%", $input);
      $s = "'%$input%'";
      //print_r($s);
      $sql = self::getBy(
        'product p join category c on p.category_id=c.category_id 
                   join branch b on b.branch_id=p.branch_id',
        "p.name like $s or p.url like $s or b.url like $s or c.url like $s or b.name like $s or c.name like $s");
      
      return Provider::paginate($sql, $page, $ipp);
    }

    static function getList($req, $default_ipp){
      $page = intval($req["page"]);
      $ipp = $default_ipp;
      $isBranchExists = Util::isKeyExists('branch', $req);
      $isCategoryExists = Util::isKeyExists('category', $req);

      if (Util::isKeyExists('ipp', $req)){
        $ipp = intval($req['ipp']);
      }

      $sql = null;
      if ($isBranchExists && $isCategoryExists){
        $sql = ProductService::getByCategoryAndBranch($req['category'], $req['branch'], $page, $ipp);
      }
      else if ($isBranchExists){
        $sql = ProductService::getByBranch($req['branch'], $page, $ipp);
      }
      else if ($isCategoryExists){
        $sql = ProductService::getByCategory($req['category'], $page, $ipp);
      }
      if ($sql != null){
        // Order
        if (Util::isKeyExists('orderby', $req)){
          $orderby = $req['orderby'];
          $mode = $req['mode'];
          $sql->order("p.$orderby $mode");
        }
        // Get by price range
        if (Util::isKeyExists('price_from', $req) && Util::isKeyExists('price_to', $req)){
          $price_from = $req['price_from'];
          $price_to = $req['price_to'];

          $sql->where($sql->qwhere. " and (p.price >= $price_from and p.price <= $price_to)");
        }
        
        return Provider::paginate($sql, $page, $ipp);
      }
      return null;
    }

    static function getBy(string $from, string $where){
      return SqlBuilder::from($from)
        ->where($where)
        ->select('p.*');
    }

    static function getByBranch(string $url){
      return self::getBy('product p join branch b on p.branch_id=b.branch_id', "b.url = '$url'");
    }
    
    static function getByCategory(string $url){
      return self::getBy('product p join category c on p.category_id=c.category_id', "c.url = '$url'");
    }

    static function getByCategoryAndBranch(string $category, string $branch){
      return self::getBy(
        'product p join category c on p.category_id=c.category_id 
                   join branch b on b.branch_id=p.branch_id', 
        "c.url = '$category' and b.url = '$branch'");
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