<?php 
  require_once "../server/index.php";
  require_once "Authen.php";
  
  $page = 1;

  if (isset($_GET['page'])){
    $page = $_GET['page'];
  }

  $_SESSION['last_url'] = GetPath();

  $items = BranchService::getAllPaginate($page);

  require_once "component/Head.php";
  require_once "component/Nav.php";
?>

<div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Brands</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-lg-12">
      <ul class="nav nav-tabs" style="margin-bottom: 30px">
        <li role="presentation" class="active"><a style="cursor: pointer">List</a></li>
        <li role="presentation"><a href="./InsertBrand.php" style="cursor: pointer">Insert</a></li>
      </ul>
      <div>
        <h4 style="margin-top: 20px"><small>Total: <?php echo $items['total'] ?> items</small></h4>
        <table width="100%" class="table table-bordered table-hover" id="dataTables-example">
          <thead>
            <tr>
              <th>ID</th>
              <th>NAME</th>
              <th>URL</th>
              <th class="text-center">UPDATE</th>
              <th class="text-center">DELETE</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              foreach($items['data'] as $k => $item){
            ?>
              <tr class="gradeX">
                <td><?php echo $item['BRANCH_ID'] ?></td>
                <td><?php echo $item['NAME'] ?></td>
                <td><?php echo $item['URL'] ?></td>
                <td class="text-center">
                  <a href="./UpdateBrand.php?id=<?php echo $item['BRANCH_ID'] ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
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
      </div>
    </div>
    <!-- /.col-lg-12 -->
  </div>
</div>

<?php require_once "component/Script.php" ?>