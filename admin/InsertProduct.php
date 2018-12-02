<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  $_SESSION['last_url'] = GetPath();

  $brands = BranchService::getAll();
  $categories = CategoryService::getAll();

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
        <li role="presentation"><a href="./Products.php" style="cursor: pointer">List</a></li>
        <li role="presentation" class="active"><a style="cursor: pointer">Insert</a></li>
      </ul>
      <div>
        <?php if (isset($_SESSION['insert_product_errors'])){ ?>
          <div class="alert alert-danger" role="alert">
            <strong>Insert failed!</strong>
            <ul>
              <?php foreach($_SESSION['insert_product_errors'] as $k => $v) { ?>
                <li><?php echo $v ?></li>
              <?php } ?> 
            </ul>
          </div>
          <?php unset($_SESSION['insert_product_errors']) ?>
        <?php } ?>
        <form action="./InsertProductPost.php" method="post">
          <div class="form-group">
            <label>BRAND</label>
            <select name="brand" class="form-control">
              <?php foreach($brands as $k => $brand) { ?>
                <option value="<?php echo $brand['BRANCH_ID'] ?>"><?php echo $brand['NAME'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>CATEGORY</label>
            <select name="category" class="form-control">
              <?php foreach($categories as $k => $c) { ?>
                <option value="<?php echo $c['CATEGORY_ID'] ?>"><?php echo $c['NAME'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>NAME</label>
            <input type="text" name="name" class="form-control">
          </div>
          <div class="form-group">
            <label>URL</label>
            <input type="text" name="url" class="form-control">
          </div>
          <div class="form-group">
            <label>SUBTITLE</label>
            <input type="text" name="subtitle" class="form-control">
          </div>
          <div class="form-group">
            <label>PRICE</label>
            <input type="number" name="price" class="form-control" min="0">
          </div>
          <div class="form-group">
            <label>DETAIL</label>
            <textarea name="detail" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <label>QUANTITY</label>
            <input type="number" name="quantity" class="form-control" min="0">
          </div>
          <button type="submit" class="btn btn-default">Submit</button>
        </form>
      </div>
    <!-- /.col-lg-12 -->
  </div>
  
  
</div>

<?php require_once "component/Script.php" ?>