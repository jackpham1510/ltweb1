<?php 
  require_once "provider/Provider.php";
  require_once "core/Util.php";

  class ProductService{

    static function getAll(int $page = 1){
      $sql = SqlBuilder::from('product')
        ->select();

      return Provider::paginate($sql, $page, 20);
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
        ->where("url=?")
        ->select()
        ->build();
      
      return Provider::select($sql, 's', [$url])[0];
    }

    static function search(string $input, int $page, int $ipp){
      $input = preg_replace('/[\W_]+/', "%", $input);
      $s = "%$input%";
      //print_r($s);
      $sql = self::getBy(
        'product p join category c on p.category_id=c.category_id 
                   join branch b on b.branch_id=p.branch_id',
        "p.name like ? or p.url like ? or b.url like ? or c.url like ? or b.name like ? or c.name like ?");
      
      return Provider::paginate($sql, $page, $ipp, 'ssssss', [$s, $s, $s, $s, $s, $s]);
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
      $types = "";
      $params = [];

      if ($isBranchExists && $isCategoryExists){
        $sql = ProductService::getByCategoryAndBranch();
        $types = "ss";
        array_push($params, $req['category'], $req['branch']);
      }
      else if ($isBranchExists){
        $sql = ProductService::getByBranch();
        $types = 's';
        array_push($params, $req['branch']);
      }
      else if ($isCategoryExists){
        $sql = ProductService::getByCategory();
        $types = 's';
        array_push($params, $req['category']);
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
          $types .= 'ii';
          array_push($params, $price_from, $price_to);

          $sql->where($sql->qwhere. " and (p.price >= ? and p.price <= ?)");
        }
        
        return Provider::paginate($sql, $page, $ipp, $types, $params);
      }
      return null;
    }

    static function getBy(string $from, string $where){
      return SqlBuilder::from($from)
        ->where($where)
        ->select('p.*');
    }

    static function getByBranch(){
      return self::getBy('product p join branch b on p.branch_id=b.branch_id', "b.url = ?");
    }
    
    static function getByCategory(){
      return self::getBy('product p join category c on p.category_id=c.category_id', "c.url = ?");
    }

    static function getByCategoryAndBranch(){
      return self::getBy(
        'product p join category c on p.category_id=c.category_id 
                   join branch b on b.branch_id=p.branch_id', 
        "c.url = ? and b.url = ?");
    }

    static function incView(string $url){
      $sql = SqlBuilder::from('product')
        ->where("url=?")
        ->update('view=view+1')
        ->build();
      
      Provider::query($sql, 's', [$url]);
    }
  }
?>