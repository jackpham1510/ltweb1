<?php 
  class ProductService{

    static function getAll(int $page = 1){
      $sql = SqlBuilder::from('product p left join branch b on p.branch_id=b.branch_id 
                                         left join category c on c.category_id=p.category_id')
        ->select('p.*, b.NAME as BRANCH_NAME, c.NAME as CATEGORY_NAME, b.URL as BRANCH_URL, c.URL as CATEGORY_URL');
      
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

    static function getInIdList($idList){
      $sql = SqlBuilder::from('product')
      ->where("product_id in $idList")
      ->select()
      ->build();
    
      return Provider::select($sql);
    }

    static function search(string $input, int $page, int $ipp){
      $input = preg_replace('/[\W_]+/', "%", $input);
      $s = "%$input%";
      //print_r($s);
      $sql = self::getBy(
        'product p left join category c on p.category_id=c.category_id 
                   left join branch b on b.branch_id=p.branch_id',
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

          //echo $sql->qwhere. " and (p.price >= ? and p.price <= ?)";

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

    static function getByName(string $name) {
      $sql = self::getBy("product p", "p.name = ?")->build();
      $rs = Provider::select($sql, 's', [$name]);
      return isset($rs) ? $rs[0] : null;
    }

    static function getByUrl(string $url){
      $sql = self::getBy("product p", "p.url = ?")->build();
      $rs = Provider::select($sql, 's', [$url]);
      return isset($rs) ? $rs[0] : null;
    }

    static function incView(string $url){
      $sql = SqlBuilder::from('product')
        ->where("url=?")
        ->update('view=view+1')
        ->build();
      
      Provider::query($sql, 's', [$url]);
    }

    static function insert($product){
      return Provider::transaction(function () use($product) {
        $sql = SqlBuilder::from("product(branch_id, category_id, name, subtitle, url, price, view, sold, detail, quantity)")
          ->insert("?, ?, ?, ?, ?, ?, ?, ?, ?, ?")
          ->build();
        
        return Provider::query($sql, "iisssiiisi", [
          $product["branch_id"], $product["category_id"], $product["name"],
          $product["subtitle"], $product["url"], $product["price"], 
          0, 0, $product["detail"], $product["quantity"]
        ], true);
      });
    }

    static function update($product){
      return Provider::transaction(function () use($product){
        $sql = SqlBuilder::from("product")
          ->update("branch_id = ?, category_id = ?, name = ?, subtitle = ?, url = ?, price = ?, view = ?, sold = ?, detail = ?, quantity = ?")
          ->where("product_id = ?")
          ->build();
        
        return Provider::query($sql, "iisssiiisii", [
          $product["branch_id"], $product["category_id"], $product["name"],
          $product["subtitle"], $product["url"], $product["price"], 
          0, 0, $product["detail"], $product["quantity"], $product["id"]
        ], true);
      });
    }

    static function delete($url){
      return Provider::transaction(function () use($url) {
        $sql = SqlBuilder::from("product")
          ->where("url = ?")
          ->delete()
          ->build();

        return Provider::query($sql, "s", [$url], true);
      });
    }
  }
?>