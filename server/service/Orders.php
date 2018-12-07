<?php 
  class OrderService {
    static function insert($order){
      $res = [ 
        "success" => false
      ];

      Provider::transaction(function () use ($order, &$res){  
        $sql = SqlBuilder::from('orders(username, price)')
          ->insert('?, ?')
          ->build();

        //print_r($order);
        $last_id = Provider::insertAndGetLastID($sql, 'si', [$order['username'], $order['total']]);
      
        if ($last_id == null){
          return false;
        }
  
        $items = $order['items'];
        $sql = SqlBuilder::from('orders_detail(order_id, product_id, quantity)')
          ->insert('?, ?, ?')
          ->build();

        $updateQuanitySql = SqlBuilder::from('product')
          ->update('quantity = (quantity - ?)')
          ->where('product_id = ?')
          ->build();
        
        foreach($items as $idx => $item){
          if (!Provider::query($sql, 'iii', [$last_id, $item['PRODUCT_ID'], $item['quantity']], true)){
            return false;
          }
        }

        $sql = SqlBuilder::from('product')
          ->where('quantity < 0')
          ->select()
          ->build();

        $products = Provider::select($sql);
        $outOfStock = [];

        if ($products != null){
          foreach($products as $k => $product){
            array_push($outOfStock, ["name" => $product['NAME']]);
          }
          $res["outOfStock"] = $outOfStock;
          return false;
        }

        $res["success"] = true;
        $res["orderID"] = $last_id;

        return true;
      });

      return $res;
    }

    static function getList($username, $page){
      $sql = SqlBuilder::from('orders')
        ->where('username = ?')
        ->order('order_date desc')
        ->select();
      
      return Provider::paginate($sql, $page, 10, 's', [$username]);
    }

    static function getOrderDetail($oder_id){
      $sql = SqlBuilder::from('orders_detail od join product p on od.product_id=p.product_id')
        ->where('od.order_id = ?')
        ->select('p.NAME, p.URL, p.BRANCH_ID, p.CATEGORY_ID, p.PRICE, p.DETAIL, od.quantity')
        ->build();
      
      return Provider::select($sql, "i", [$oder_id]);
    }

    static function getAll($page = 1){
      $sql = SqlBuilder::from('orders')
        ->order('order_date desc')
        ->select();
      
      return Provider::paginate($sql, $page, 20);
    }

    static function updateStatus($id, $status){
      return Provider::transaction(function () use($id, $status) {
        $sql = SqlBuilder::from('orders')
          ->where('order_id = ?')
          ->update('status = ?')
          ->build();

        return Provider::query($sql, 'ii', [$status, $id], true);
      });
    }
    /*
      select DAY(ORDER_DATE), MONTH(ORDER_DATE), YEAR(ORDER_DATE), count(*) as TOTAL
      from orders
      group by DAY(ORDER_DATE), MONTH(ORDER_DATE), YEAR(ORDER_DATE)
      order by DAY(ORDER_DATE) desc, MONTH(ORDER_DATE) desc, YEAR(ORDER_DATE) desc
      limit 10 
    */
    static function getOrdersLast10Day(){
      $sql = SqlBuilder::from('orders')
        ->group('DAY(ORDER_DATE), MONTH(ORDER_DATE), YEAR(ORDER_DATE)')
        ->order('DAY(ORDER_DATE) desc, MONTH(ORDER_DATE) desc, YEAR(ORDER_DATE) desc')
        ->limit(10)
        ->select('DAY(ORDER_DATE) as ORDER_DAY, MONTH(ORDER_DATE) as ORDER_MONTH, YEAR(ORDER_DATE) as ORDER_YEAR, count(*) as TOTAL')
        ->build();
      
      return Provider::select($sql);
    }
  }
?>