<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  $_SESSION['last_url'] = GetPath();

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
        <li role="presentation"><a href="./Brands.php" style="cursor: pointer">List</a></li>
        <li role="presentation" class="active"><a style="cursor: pointer">Insert</a></li>
      </ul>
      <div>
        <?php if (isset($_SESSION['insert_brand_errors'])){ ?>
          <div class="alert alert-danger" role="alert">
            <strong>Insert failed!</strong>
            <ul>
              <?php foreach($_SESSION['insert_brand_errors'] as $k => $v) { ?>
                <li><?php echo $v ?></li>
              <?php } ?> 
            </ul>
          </div>
          <?php unset($_SESSION['insert_brand_errors']) ?>
        <?php } ?>
        <form action="./InsertBrandPost.php" method="post">
          <div class="form-group">
            <label>NAME</label>
            <input type="text" name="name" class="form-control">
          </div>
          <div class="form-group">
            <label>URL</label>
            <input type="text" name="url" class="form-control">
          </div>
          <button type="submit" class="btn btn-default">Submit</button>
        </form>
      </div>
    <!-- /.col-lg-12 -->
  </div>
  
  
</div>

<?php require_once "component/Script.php" ?>