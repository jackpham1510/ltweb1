<?php 
  require_once "../server/index.php";
  require_once "Authen.php";

  $_SESSION['last_url'] = GetPath();

  $brands = BranchService::getAll();
  $categories = CategoryService::getAll();

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
        <li role="presentation" class="active"><a style="cursor: pointer">Insert</a></li>
      </ul>
      <?php if (isset($_SESSION['insert_product_error'])){ ?>
        <div class="alert alert-danger" role="alert">
          <strong>Insert failed!</strong>
          <ul>
            <?php foreach($_SESSION['insert_product_error'] as $k => $v) { ?>
              <li><?php echo $v ?></li>
            <?php } ?> 
          </ul>
        </div>
        <?php unset($_SESSION['insert_product_error']) ?>
      <?php } ?>
      <form action="./InsertProductPost.php" method="post" enctype="multipart/form-data">
      <h4 style="margin-bottom: 20px"><small>Note: Only accept .png image</small></h4>
      <div class="row" data-bind="foreach: images">
        <div class="col-lg-2">
          <div class="panel panel-primary">
            <div class="panel-heading pannel-title" data-bind="text: 'Image '+($index()+1)"></div>
            <input type="file" accept=".png" style="display: none" data-bind="attr: { id: 'image'+$index(), name: 'image'+$index() },   event: { change: function(data, e) { $root.chooseImage(e, $index()) } }" />
            <img style="width: 100%" alt="No Image" data-bind="visible: hasImage, attr: { id: 'imgDisplay'+$index() }" />
            <label class="form-control" style="margin: 0; cursor: pointer" data-bind="attr: { for: 'image'+$index() }">
              <span style="float: left">Choose Image</span>
              <span style="float: right"><i class="fa fa-plus"></i></span>
              <div class="clearfix"></div>
            </label>
            <label class="form-control" style="margin: 0; cursor: pointer" data-bind="visible: hasImage, click: function(){ $root.removeImage($index()) }">
              <span style="float: left">Remove</span>
              <span style="float: right"><i class="fa fa-times"></i></span>
              <div class="clearfix"></div>
            </label>
          </div>
          <h4><input type="radio" name="selectedImage" data-bind="enable: hasImage, attr: { value: $index() }, checked: $root.selectedImage" /> <small>Set as default</small></h4>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group">
            <label>CATEGORY</label>
            <select name="category" class="form-control">
              <?php foreach($categories as $k => $c) { ?>
                <option value="<?php echo $c['CATEGORY_ID'] ?>"><?php echo $c['NAME'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>BRAND</label>
            <select name="brand" class="form-control">
              <option value="null">Null</option>
              <?php foreach($brands as $k => $brand) { ?>
                <option value="<?php echo $brand['BRANCH_ID'] ?>"><?php echo $brand['NAME'] ?></option>
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
            <label>QUANTITY</label>
            <input type="number" name="quantity" class="form-control" min="0">
          </div>
          <div class="form-group">
            <label>PRICE</label>
            <input type="number" name="price" class="form-control" min="0">
          </div>
          <div class="form-group">
            <label>DESCRIPTION</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
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
                  <td><input type="text" class="form-control" placeholder="Name of spec" data-bind="attr: { name: 'spec-name'+$index() }"></td>
                  <td><input type="text" class="form-control" placeholder="Value of spec" data-bind="attr: { name: 'spec-value'+$index() }"></td>
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
    { hasImage: ko.observable(false) }
  ]);

  self.specs = ko.observableArray([null]);
  self.selectedImage = ko.observable("0")

  self.addSpec = function (){
    self.specs.push(null);
  }

  self.removeSpec = function (idx){
    self.specs.splice(idx, 1);
  }

  self.readUrl = function (input, idx) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#imgDisplay"+idx).attr('src', e.target.result);
        let item = self.images()[idx]
        item.hasImage(true);
        self.images.push({ hasImage: ko.observable(false) })
      }

      reader.readAsDataURL(input.files[0]);
    }
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


// $("#img").change(function(){
//   readURL(this);
// });
</script>