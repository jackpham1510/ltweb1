<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  $page = 1;
  $_SESSION['last_url'] = GetPath();

  if (isset($_GET['page'])){
    $page = $_GET['page'];
  }

  $items = OrderService::getAll($page);
  $status = Config::getValue('order_status');

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
        <li role="presentation" class="active"><a style="cursor: pointer">List</a></li>
      </ul>
      <div>
        <h4 style="margin-top: 20px"><small>Total: <?php echo $items['total'] ?> items</small></h4>
        <table width="100%" class="table table-bordered table-hover" id="dataTables-example">
          <thead>
            <tr>
              <th>ID</th>
              <th>USERNAME</th>
              <th>ORDER_DATE</th>
              <th>RECEIVE_DATE</th>
              <th>PRICE</th>
              <th class="text-center">STATUS</th>
              <th class="text-center">UPDATE</th>
              <th class="text-center">DELETE</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              foreach($items['data'] as $k => $item){
            ?>
              <tr class="gradeX <?php echo $item['STATUS'] === 2 ? 'bg-success' : '' ?>">
                <td><?php echo $item['ORDER_ID'] ?></td>
                <td><?php echo $item['USERNAME'] ?></td>
                <td><?php echo $item['ORDER_DATE'] ?></td>
                <td><?php echo $item['RECEIVE_DATE'] ?></td>
                <td><?php echo $item['PRICE'] ?></td>
                <td class="text-center">
                  <select class="form-control">
                    <?php foreach($status as $k => $v) { ?>
                      <option value="<?php echo $k ?>" <?php echo $item['STATUS'] == $k ? 'selected' : '' ?>><?php echo $v ?></option>
                    <?php } ?>
                  </select>
                </td>
                <td class="text-center"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></td>
                <td class="text-center"><button type="button" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <div class="row">
          <div class="col-sm-12" style="display: flex; justify-content: center">
            <div class="dataTables_paginate paging_simple_numbers">
              <ul class="pagination" style="float: right">
                <li class="paginate_button" aria-controls="dataTables-example">
                  <a href="?page=1">First</a>
                </li>
                <?php for($i = 1; $i <= $items['totalPages']; $i++){ ?>
                  <li class="paginate_button <?php echo $i === $items['active'] ? 'active' : '' ?>" aria-controls="dataTables-example">
                    <a href="?page=<?php echo $i ?>"><?php echo $i ?></a>
                  </li>
                <?php } ?>
                <li class="paginate_button" aria-controls="dataTables-example">
                  <a href="?page=<?php echo $items['totalPages'] ?>">Last</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    <!-- /.col-lg-12 -->
  </div>
  
  
</div>

<?php require_once "component/Script.php" ?>