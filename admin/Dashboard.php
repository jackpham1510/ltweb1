<?php
  require_once "../server/index.php";
  require_once "./Authen.php";
  require_once "component/Head.php";
  require_once "component/Nav.php";

  $data = OrderService::getOrdersLast10Day();
?>

<div id="page-wrapper">
  <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
      </div>
      <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  <div class="row">
    <div class="col-lg-3 col-md-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-comments fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge">26</div>
              <div>Item Sold!</div>
            </div>
          </div>
        </div>
        <a href="#">
          <div class="panel-footer">
            <span class="pull-left">View Details</span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="panel panel-green">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-comments fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge">26</div>
              <div>New Users!</div>
            </div>
          </div>
        </div>
        <a href="#">
          <div class="panel-footer">
            <span class="pull-left">View Details</span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="panel panel-yellow">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-comments fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge">26</div>
              <div>New Orders!</div>
            </div>
          </div>
        </div>
        <a href="#">
          <div class="panel-footer">
            <span class="pull-left">View Details</span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="panel panel-red">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-3">
              <i class="fa fa-comments fa-5x"></i>
            </div>
            <div class="col-xs-9 text-right">
              <div class="huge">26</div>
              <div>Item out of stocks!</div>
            </div>
          </div>
        </div>
        <a href="#">
          <div class="panel-footer">
            <span class="pull-left">View Details</span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-green">
        <div class="panel-heading">
          <h3 class="panel-title">Total Orders Last 10 Days</h3>
        </div>
        <div class="panel-body">
          <div id="order-chart" style="height: 250px;"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Products</h3>
        </div>
        <div class="panel-body">
          <p>
            <a href="./Products.php"><button class="btn btn-primary">List</button></a>
            <a href="./InsertProduct.php"><button class="btn btn-primary">Insert</button></a>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Brands</h3>
        </div>
        <div class="panel-body">
          <p>
            <a href="./Brands.php"><button class="btn btn-primary">List</button></a>
            <a href="./InsertBrand.php"><button class="btn btn-primary">Insert</button></a>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Categories</h3>
        </div>
        <div class="panel-body">
          <p>
            <a href="./Categories.php"><button class="btn btn-primary">List</button></a>
            <a href="./InsertCategory.php"><button class="btn btn-primary">Insert</button></a>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Orders</h3>
        </div>
        <div class="panel-body">
          <a href="./Orders.php"><button class="btn btn-primary">List</button></a>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Users</h3>
        </div>
        <div class="panel-body">
          <a href="./Users.php"><button class="btn btn-primary">List</button></a>
        </div>
      </div>
    </div>
  </div>
    
      
      
      
</div>

<?php require_once "component/Script.php" ?>
<script>
new Morris.Bar({
  // ID of the element in which to draw the chart.
  element: 'order-chart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    <?php foreach ($data as $k => $item) { ?>
      { day: '<?php echo $item['ORDER_YEAR'].'-'.$item['ORDER_MONTH'].'-'.$item['ORDER_DAY'] ?>', value: <?php echo $item['TOTAL'] ?> },
    <?php } ?>
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'day',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Total Orders'],
  barColors: ['#42b983']
});
</script>