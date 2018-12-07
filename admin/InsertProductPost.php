<?php
  require_once "../server/index.php";
  require_once "./Authen.php";

  function getImages($url){
    $images = [];
    foreach($_FILES as $key => $file){
      if (is_file_ok($file) && FilterFileType($file, ["png"])){
        $images[$key] = "$url-$key.png";
      }
    }
    return $images;
  }

  function getSpecs(){
    $i = 0;
    $specs = [];
    while (isset($_POST["spec-name$i"]) 
    && isset($_POST["spec-value$i"])
    && !empty($_POST["spec-name$i"])
    && !empty($_POST["spec-value$i"])) {
      array_push($specs, [$_POST["spec-name$i"], $_POST["spec-value$i"]]);
      $i++;
    }
    return count($specs) > 0 ? $specs : null;
  }

  function generateDetail($images, $specs, $desc){
    return Util::jsonEncode([
      "images" => [
        "selected" => "image".($_POST["selectedImage"] ?? "0"),
        "items" => $images
      ],
      "desc" => $desc,
      "spec" => $specs
    ]);
  }

  if (!empty($_POST)){
    $errors = [];
    $name = $_POST["name"];
    $url = MakeUrl($_POST["url"]);
    $subtitle = $_POST["subtitle"];
    $quantity = intval($_POST["quantity"]);
    $price = intval($_POST["price"]);
    $desc = $_POST["description"];
    $brand_id = $_POST["brand"] !== "null" ? intval($_POST["brand"]) : null;
    $category_id = intval($_POST["category"]);
    $images = [];
    $specs = [];

    empty($name) && array_push($errors, "Name is required");
    empty($url) && array_push($errors, "URL is required");
    $ok = empty($errors);
    $brand = $ok && isset($brand_id) ? BranchService::getById($brand_id) : ["URL" => "tat-ca"];
    $category = $ok ? CategoryService::getById($category_id) : null;
    $ok = $ok && isset($brand) && isset($category);
    $ok && !is_null(ProductService::getByName($name)) && array_push($errors, "Name is existed");
    $ok && !is_null(ProductService::getByUrl($url)) && array_push($errors, "URL is existed");
    $quantity <= 0 && array_push($errors, "Quantity must greater than zero");
    $price < 1000 && array_push($errors, "Price must greater than 1000");

    $images = getImages($url); 
    $hasImage = count($images) > 0;
    $hasImage || array_push($errors, "Image is required");
    $ok = empty($errors) && $hasImage;
    
    if ($ok){
      $specs = getSpecs();
      $detail = generateDetail($images, $specs, $_POST["description"]);
      //print_r($detail);

      $dir = Config::getValue("client_images")."/details/".$category['URL']."/".$brand["URL"];
      if (!file_exists($dir)){
        mkdir($dir, 0777, true);
      }

      foreach ($images as $key => $imgPath){ 
        SaveFile($_FILES[$key], $dir, $imgPath);
      }

      $ok = $ok && ProductService::insert([
        "branch_id" => $brand_id,
        "category_id" => $category_id,
        "name" => $name,
        "subtitle" => $subtitle,
        "url" => $url,
        "price" => $price,
        "quantity" => $quantity,
        "detail" => $detail
      ]);
    }

    //print_r($errors);

    if (!$ok) {
      array_push($errors, 'Insert failed, please try again!');
      $_SESSION['insert_product_error'] = $errors;
      Redirect($_SESSION['last_url']);
    }
    else {
      $_SESSION['rs_message'] = CreateRsMessage(true, "Insert product '$name' success");
      Redirect('./Products.php');
    }
  }
?> 