<?php
  require_once "provider/Provider.php";
  require_once "core/Router.php";

  require_once "routes/Product.php";

  // foreach($_SERVER as $key => $value){
  //   print_r("$key => $value<br>");
  // }
  //print_r($_GET["a"]);

  // echo SqlQuery::from('BRANCH')->select("NAME, URL");
  // echo "<br>";
  // echo SqlQuery::from('BRANCH')->insert([
  //   "NAME" => "hihi",
  //   "URL" => "hi-hi"
  // ]);
  // echo "<br>";
  // echo SqlQuery::from('BRANCH')
  //   ->where("BRANCH_ID >= 5")
  //   ->update([
  //     "NAME" => "hihi"
  //   ]);
  // echo "<br>";
  // echo SqlQuery::from('BRANCH')
  //   ->where("NAME='hihi'")
  //   ->delete();
  
  // echo "<br>";
  Router::get('/', function (){
    echo "Hello World";
  });

  Router::resolve();
  
  //error_log('Error');
  // $serername = "localhost:3306";
  // $username = "root";
  // $password = "";
  // $dbname = "ltweb1";

  // $connection = new mysqli($serername, $username, $password, $dbname);

  // if ($connection->connect_error){
  //   die("Connection failed: ". $connection->connect_error);
  // }

  // $connection->set_charset('utf8');

  // echo "Connected Successfully<br/>";

  // function showTable($result){
  //   if ($result->num_rows > 0){
  //     echo '<table border="1" cellpadding="10">';
  //     $row = $result->fetch_assoc();
  //     echo "<tr>";
  //     foreach($row as $column => $value){
  //       echo "<th>$column</th>";
  //     }
  //     echo "</tr>";
  //     do{
  //       echo "<tr>";
  //       foreach($row as $column => $value){
  //         echo "<th>$value</th>";
  //       }
  //       echo "<tr>";
  //     } while ($row = $result->fetch_assoc());
  //     echo "</table>";
  //   }
  // }

  // // echo '<h1>BRANCH</h1>';
  // // showTable($connection->query('select * from BRANCH'));
  // // echo '<h1>CATEGORY</h1>';
  // // showTable($connection->query('select * from CATEGORY'));
  // // echo '<h1>USERS</h1>';
  // // showTable($connection->query('select * from USERS'));
  // // echo '<h1>PRODUCT</h1>';
  // // showTable($connection->query('select * from PRODUCT'));

  // include "product.php";

  // product\hello();

  // $connection->close();
?>