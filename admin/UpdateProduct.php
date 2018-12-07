<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  if (!isset($_GET['url'])){
    Redirect('/Products.php');
    return;
  }

  $_SESSION['last_url'] = GetPath();

  $product = ProductService::getByUrl($_GET['url']);

  $brands = BranchService::getAll();
  $categories = CategoryService::getAll();

  $detail = json_decode($product['DETAIL'], true);
  $images = $detail["images"];
  $specs = $detail["spec"];
  $selectedImage = $images["selected"];
  $selectedImageIdx = 0;

  //var_dump($detail);

  foreach ($images["items"] as $key => $img){
    if ($key === $selectedImage){
      break;
    }
    $selectedImageIdx++;
  }

  $cUrl = ""; $bUrl = null;
  foreach($categories as $k => $v){
    if ($v["CATEGORY_ID"] === $product["CATEGORY_ID"]){
      $cUrl = $v["URL"];
      break;
    }
  }
  foreach($brands as $k => $v){
    if ($v["BRANCH_ID"] === $product["BRANCH_ID"]){
      $bUrl = $v["URL"];
      break;
    }
  }

  $bUrl = $bUrl ?? "tat-ca";

  function GetImagePath($img) {
    global $cUrl, $bUrl;
    return Config::getValue('client_images')."/details/$cUrl/$bUrl/$img";
  }

  require_once "component/Head.php";
  require_once "component/Nav.php";
?>

<div id="page-wrapper" style="padding-bottom: 40px">
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
        <li role="presentation"><a href="./InsertProduct.php" style="cursor: pointer">Insert</a></li>
        <li role="presentation" class="active"><a style="cursor: pointer">Update</a></li>
      </ul>
      <?php if (isset($_SESSION['update_product_error'])){ ?>
        <div class="alert alert-danger" role="alert">
          <strong>Update failed!</strong>
          <ul>
            <?php foreach($_SESSION['update_product_error'] as $k => $v) { ?>
              <li><?php echo $v ?></li>
            <?php } ?> 
          </ul>
        </div>
        <?php unset($_SESSION['update_product_error']) ?>
      <?php } ?>
      <form action="./UpdateProductPost.php" method="post" enctype="multipart/form-data">
      <h4 style="margin-bottom: 20px"><small>Note: Only accept .png image</small></h4>
      <input type="hidden" name="oldUrl" value="<?php echo $product['URL'] ?>"/>
      <div class="row" data-bind="foreach: images">
        <div class="col-lg-2">
          <div class="panel panel-primary">
            <div class="panel-heading pannel-title" data-bind="text: 'Image '+($index()+1)"></div>
            <input type="hidden" data-bind="attr: { name: key, value: true }" />
            <input type="file" accept=".png" style="display: none" data-bind="attr: { id: 'image'+$index(), name: 'image'+$index() }, event: { change: function(data, e) { $root.chooseImage(e, $index()) } }" />
            <img style="width: 100%" alt="No Image" data-bind="visible: url, attr: { name: 'imgDisplay'+$index(), src: url }" />
            <label class="form-control" style="margin: 0; cursor: pointer" data-bind="attr: { for: 'image'+$index() }">
              <span style="float: left">Choose Image</span>
              <span style="float: right"><i class="fa fa-plus"></i></span>
              <div class="clearfix"></div>
            </label>
            <label class="form-control" style="margin: 0; cursor: pointer" data-bind="visible: url, click: function(){ $root.removeImage($index()) }">
              <span style="float: left">Remove</span>
              <span style="float: right"><i class="fa fa-times"></i></span>
              <div class="clearfix"></div>
            </label>
          </div>
          <h4><input type="radio" name="selectedImage" data-bind="enable: url, attr: { value: $index() }, checked: $root.selectedImage" /> <small>Set as default</small></h4>
        </div>
      </div>
      <button class="btn btn-primary" style="margin: 20px 0" data-bind="click: $root.addImage"><i class="fa fa-plus"></i> Add Image</button>
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group">
            <label>CATEGORY</label>
            <select name="category" class="form-control">
              <?php foreach($categories as $k => $c) { ?>
                <option value="<?php echo $c['CATEGORY_ID'] ?>" 
                <?php echo $product["CATEGORY_ID"] == $c["CATEGORY_ID"] ? "selected" : "" ?>><?php echo $c['NAME'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>BRAND</label>
            <select name="brand" class="form-control">
              <option value="null">Null</option>
              <?php foreach($brands as $k => $brand) { ?>
                <option value="<?php echo $brand['BRANCH_ID'] ?>"
                <?php echo $product["BRANCH_ID"] == $brand["BRANCH_ID"] ? "selected" : "" ?>><?php echo $brand['NAME'] ?></option>
              <?php } ?>
            </select>
          </div>
          <input type="hidden" name="id" class="form-control" value="<?php echo $product["PRODUCT_ID"] ?>">
          <div class="form-group">
            <label>NAME</label>
            <input type="text" name="name" class="form-control" value="<?php echo $product["NAME"] ?>">
          </div>
          <div class="form-group">
            <label>URL</label>
            <input type="text" name="url" class="form-control" value="<?php echo $product["URL"] ?>">
          </div>
          <div class="form-group">
            <label>SUBTITLE</label>
            <input type="text" name="subtitle" class="form-control" value="<?php echo $product["SUBTITLE"] ?>">
          </div>
          <div class="form-group">
            <label>QUANTITY</label>
            <input type="number" name="quantity" class="form-control" min="0" value="<?php echo $product["QUANTITY"] ?>">
          </div>
          <div class="form-group">
            <label>PRICE</label>
            <input type="number" name="price" class="form-control" min="0" value="<?php echo $product["PRICE"] ?>">
          </div>
          <div class="form-group">
            <label>DESCRIPTION</label>
            <textarea name="description" class="form-control" rows="4"><?php echo $detail["desc"] ?></textarea>
          </div>
          <div class="form-group">
            <label>SPECIFICATIONS</label>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Value</th>
                  <th>Remove</th>
                </tr>
              </thead>
              <tbody data-bind="foreach: specs">
                <tr>
                  <td><input type="text" class="form-control" placeholder="Name of spec" data-bind="attr: { name: 'spec-name'+$index(), value: specName }"></td>
                  <td><input type="text" class="form-control" placeholder="Value of spec" data-bind="attr: { name: 'spec-value'+$index(), value: specValue }"></td>
                  <td style="width: 50px"><div class="btn btn-danger" data-bind="click: function(){ $root.removeSpec($index()) }"><i class="fa fa-times"></i></div></td>
                </tr>
              </tbody>
            </table>
            <div class="btn btn-primary" data-bind="click: addSpec"><i class="fa fa-plus"></i> Add Row</div>
          </div>
          <button type="submit" class="btn btn-default">Submit</button>
        </div>
      </div>
      </form>
    <!-- /.col-lg-12 -->
  </div>
  
  
</div>

<?php require_once "component/Script.php" ?>
<script src="./js/knockout-min.js"></script>
<script>
function ViewModel(){
  let self = this;
  self.images = ko.observableArray([
    <?php foreach($images["items"] as $key => $img) { ?>
      { url: ko.observable("<?php echo GetImagePath($img) ?>"), key: ko.observable("<?php echo $key ?>") },
    <?php } ?>
  ]);

  self.specs = ko.observableArray([
    <?php 
      if (isset($specs)){
        foreach($specs as $k => $v){
          $specName = $v[0]; $specValue = $v[1];
          echo "{specName: '$specName', specValue: '$specValue'},";
        }
      }
      else{
        echo "{specName: '', specValue: ''}";
      }  
    ?>
  ]);
  self.selectedImage = ko.observable("<?php echo $selectedImageIdx ?>");
  //console.log(self.selectedImage());

  self.addSpec = function (){
    self.specs.push({ specName: "", specValue: "" });
  }

  self.removeSpec = function (idx){
    self.specs.splice(idx, 1);
  }

  self.readUrl = function (input, idx) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        let item = self.images()[idx]
        item.url(e.target.result);
        item.key(null);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }

  self.addImage = function (){
    self.images.push({ url: ko.observable(false), key: ko.observable(null) });
  }

  self.chooseImage = function (e, idx){
    self.readUrl(e.target, idx);
  }

  self.removeImage = function (idx){
    self.images.splice(idx, 1);
    if (self.images().length <= 1){
      self.selectedImage("0");
    }
  }
}

ko.applyBindings(new ViewModel());

</script>