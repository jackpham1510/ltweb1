<?php 
  require_once "../server/index.php";
  require_once "Authen.php";
  
  $page = 1;

  if (isset($_GET['page'])){
    $page = $_GET['page'];
  }

  $_SESSION['last_url'] = GetPath();

  $items = CategoryService::getAllPaginate($page);

  require_once "component/Head.php";
  require_once "component/Nav.php";
?>

<div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
          <h1 class="page-header">Categories</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-lg-12">
      <ul class="nav nav-tabs" style="margin-bottom: 30px">
        <li role="presentation" class="active"><a style="cursor: pointer">List</a></li>
        <li role="presentation"><a href="./InsertCategory.php" style="cursor: pointer">Insert</a></li>
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
              <th>ICON</th>
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
                <td style="background: #333"><img src="<?php echo Config::getValue('client_images')."/icon/".$item["URL"]."-32.png" ?>" width="32"/></td>
                <td><?php echo $item['CATEGORY_ID'] ?></td>
                <td><?php echo $item['NAME'] ?></td>
                <td><?php echo $item['URL'] ?></td>
                <td class="text-center">
                  <a href="./UpdateCategory.php?id=<?php echo $item['CATEGORY_ID'] ?>"><button type="button" class="btn btn-primary"><i class="fa fa-edit"></i></button></a>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-danger" onclick="deleteItem('<?php echo $item['URL'] ?>')">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
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

<?php 
  require_once "component/Script.php";
  require_once "component/Datables.php";
?>
<script>
  function deleteItem(url){
    let rs = window.confirm('Are you sure to delete category, url: ' + url);
    if (rs){
      window.open(window.location.origin+'/ltweb1/admin/DeleteCategory.php?url='+url, '_self');
    }
  }
</script>