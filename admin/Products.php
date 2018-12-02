<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  $page = 1;

  if (isset($_GET['page'])){
    $page = $_GET['page'];
  }

  $_SESSION['last_url'] = GetPath();

  $items = ProductService::getAll($page);

  require_once "component/Head.php";
  require_once "component/Nav.php";
?>

<div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Products</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-lg-12">
      <ul class="nav nav-tabs" style="margin-bottom: 30px">
        <li role="presentation" class="active"><a style="cursor: pointer">List</a></li>
        <li role="presentation"><a href="./InsertProduct.php" style="cursor: pointer">Insert</a></li>
      </ul>
      <div>
        <h4 style="margin-top: 20px"><small>Total: <?php echo $items['total'] ?> items</small></h4>
        <table width="100%" class="table table-bordered table-hover" id="dataTables-example">
          <thead>
            <tr>
              <th>ID</th>
              <th>NAME</th>
              <th>URL</th>
              <th>SUBTITLE</th>
              <th>PRICE</th>
              <th>VIEW</th>
              <th>SOLD</th>
              <th>QUANTITY</th>
              <th>UPDATE</th>
              <th>DELETE</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              foreach($items['data'] as $k => $product){
            ?>
              <tr class="gradeX">
                <td><?php echo $product['PRODUCT_ID'] ?></td>
                <td><?php echo $product['NAME'] ?></td>
                <td><?php echo $product['URL'] ?></td>
                <td><?php echo $product['SUBTITLE'] ?></td>
                <td class="text-danger"><?php echo number_format($product['PRICE']) ?></td>
                <td><?php echo $product['VIEW'] ?></td>
                <td><?php echo $product['SOLD'] ?></td>
                <td><?php echo $product['QUANTITY'] ?></td>
                <td class="text-center">
                  <a href="./UpdateProduct.php?url=<?php echo $product['URL'] ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                </td>
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