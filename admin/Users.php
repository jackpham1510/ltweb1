<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  $page = 1;
  $_SESSION['last_url'] = '/Users.php?page=1';

  if (isset($_GET['page'])){
    $page = $_GET['page'];
  }

  $items = UserService::getAll($page);

  require_once "component/Head.php";
  require_once "component/Nav.php";
?>

<div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Users</h1>
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
        <?php 
          if (isset($_SESSION['rs_message'])) {
        ?>
          <div class="alert alert-<?php echo $_SESSION['rs_message']['success'] ? 'success' : 'danger' ?>">
            <?php echo $_SESSION['rs_message']['message'] ?>
          </div>
          <?php unset($_SESSION['rs_message']) ?>
        <?php } ?>
        <h4 style="margin-top: 20px"><small>Total: <?php echo $items['total'] ?> items</small></h4>
        <table width="100%" class="table table-bordered table-hover" id="dataTables-example">
          <thead>
            <tr>
              <th>USERNAME</th>
              <th>NAME</th>
              <th>PHONE</th>
              <th>ADDRESS</th>
              <th class="text-center">TYPE</th>
              <th class="text-center">UPDATE</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              foreach($items['data'] as $k => $item){
            ?>
              <tr class="gradeX">
                <td><?php echo $item['USERNAME'] ?></td>
                <td><?php echo $item['NAME'] ?></td>
                <td><?php echo $item['PHONE'] ?></td>
                <td><?php echo $item['ADDRESS'] ?></td>
                <form action="./UpdateUser.php" method="post">
                  <input type="hidden" name="username" value="<?php echo $item['USERNAME'] ?>" />
                  <td class="text-center">
                    <select name="type" class="form-control">
                      <option value="1" <?php echo $item['TYPE'] == 1 ? 'selected' : '' ?>>1</option>
                      <option value="2" <?php echo $item['TYPE'] == 2 ? 'selected' : '' ?>>2</option>
                    </select>
                  </td>
                  <td class="text-center"><button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button></td>
                </form>
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

<?php 
  require_once "component/Script.php";
  require_once "component/Datables.php";
?>