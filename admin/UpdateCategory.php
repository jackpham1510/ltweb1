<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  $_SESSION['last_url'] = GetPath();

  if (!isset($_GET['id'])){
    Redirect('/Categories.php');
    return;
  }
  
  $_SESSION['last_url'] = GetPath();

  $category = CategoryService::getById(intval($_GET['id']));

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
        <li role="presentation"><a href="./Categories.php" style="cursor: pointer">List</a></li>
        <li role="presentation"><a href="./InsertCategory.php" style="cursor: pointer">Insert</a></li>
        <li role="presentation" class="active"><a style="cursor: pointer">Update</a></li>
      </ul>
      <div>
        <?php if (isset($_SESSION['update_category_errors'])){ ?>
          <div class="alert alert-danger" role="alert">
            <strong>Update failed!</strong>
            <ul>
              <?php foreach($_SESSION['update_category_errors'] as $k => $v) { ?>
                <li><?php echo $v ?></li>
              <?php } ?> 
            </ul>
          </div>
          <?php unset($_SESSION['update_category_errors']) ?>
        <?php } ?>
        <form action="./UpdateCategoryPost.php" method="post">
          <div class="form-group">
            <label>NAME</label>
            <input type="text" name="name" class="form-control" value="<?php echo $category['NAME'] ?>">
          </div>
          <div class="form-group">
            <label>URL</label>
            <input type="text" name="url" class="form-control" value="<?php echo $category['NAME'] ?>">
          </div>
          <button type="submit" class="btn btn-default">Submit</button>
        </form>
      </div>
    <!-- /.col-lg-12 -->
  </div>
  
  
</div>

<?php require_once "component/Script.php" ?>