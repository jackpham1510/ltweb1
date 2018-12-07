<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  if (isset($_GET['order_id'])){
    //Redirect($_SESSION['last_url']);
  }

  $items = OrderService::getOrderDetail($_GET['order_id']);

  require_once "component/Head.php";
  require_once "component/Nav.php";
?>

<div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Orders</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-lg-12">
      <ul class="nav nav-tabs" style="margin-bottom: 30px">
        <li role="presentation"><a href=".<?php echo $_SESSION['last_url'] ?>" style="cursor: pointer">List</a></li>
        <li role="presentation" class="active"><a style="cursor: pointer">Detail</a></li>
      </ul>
      <div>
        <?php 
          if (isset($_SESSION['rs_message'])) {
        ?>
          <div class="alert alert-<?php echo $_SESSION['rs_message']['success'] ? 'success' : 'danger' ?>">
            <?php echo $_SESSION['rs_message']['message'] ?>
          </div>
          <?php unset($_SESSION['rs_message']) ?>
        <?php } ?>
        <table width="100%" class="table table-bordered table-hover" id="dataTables-example">
          <thead>
            <tr>
              <th>PRODUCT_NAME</th>
              <th>PRICE</th>
              <th>QUANTITY</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $total = 0;
              foreach($items as $k => $item){
                $total += $item['PRICE'];
            ?>
              <tr class="gradeX">
                <td><?php echo $item['NAME'] ?></td>
                <td><?php echo number_format($item['PRICE']) ?></td>
                <td><?php echo $item['quantity'] ?></td>
                </form>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <h4 style="float: right">Total: <span class="text-danger"><?php echo number_format($total) ?>đ</span></h4>
      </div>
    <!-- /.col-lg-12 -->
  </div>
  
  
</div>

<?php require_once "component/Script.php" ?>