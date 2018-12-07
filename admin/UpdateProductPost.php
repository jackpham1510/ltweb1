<?php
  require_once "../server/index.php";
  require_once "./Authen.php";

  var_dump($_FILES);
  echo "<hr>";

  var_dump($_POST);
  echo "<hr>";

  function getUploadedImages($url, $startIdx, $dir){
    $images = [];
    foreach($_FILES as $k => $file){
      if (is_file_ok($file) && FilterFileType($file, ["png"])){
        $key = "image$startIdx";
        $imgPath = "$url-$key.png";
        $images[$key] = $imgPath;
        echo "<h2 style='color: green'>Add $dir/$url-$key.png</h2>";
        SaveFile($file, $dir, $imgPath);
        $startIdx++;
      }
    }
    return $images;
  }

  function updateOldImages(&$oldImgs, $oldDir, $newDir, $url){
    $images = [];
    $idx = 0;
    foreach($oldImgs as $key => $oldPath){
      $oldImg = "$oldDir/$oldPath";

      if (isset($_POST[$key])){
        $newPath = "$url-image$idx.png";
        $images["image$idx"] = $newPath;
        echo "<h2 style='color: blue'>Rename $oldImg to $newDir/$newPath</h2>";
        rename($oldImg, "$newDir/$newPath");
        $idx++;
      }
      else {
        echo "<h2 style='color: red'>Remove $oldImg</h2>";
        unlink($oldImg);
      }
    }
    return $images;
  }

  function getDir($cUrl, $bUrl){
    $dir = Config::getValue("client_images")."/details/".$cUrl."/".$bUrl;
    if (!file_exists($dir)){
      mkdir($dir, 0777, true);
    }
    return $dir;
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
    $id = $_POST["id"];
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

    //Validation
    empty($name) && array_push($errors, "Name is required");
    empty($url) && array_push($errors, "URL is required");
    $ok = empty($errors);

    // New Brand and Category 
    $brand = $ok && isset($brand_id) ? BranchService::getById($brand_id) : ["URL" => "tat-ca"];
    $category = $ok ? CategoryService::getById($category_id) : null;

    // Still validation
    $ok = $ok && isset($brand) && isset($category);
    $byName = ProductService::getByName($name);
    $byUrl = ProductService::getByUrl($url);
    $ok && !is_null($byName) && $byName["PRODUCT_ID"] != $id  && array_push($errors, "Name is existed");
    $ok && !is_null($byUrl) && $byUrl["PRODUCT_ID"] != $id && array_push($errors, "URL is existed");
    $quantity <= 0 && array_push($errors, "Quantity must greater than zero");
    $price < 1000 && array_push($errors, "Price must greater than 1000");
    $ok = empty($errors);

    if ($ok){
      $oldUrl = $_POST["oldUrl"];
      $product = ProductService::getByUrl($oldUrl);
      $oldCategory = CategoryService::getById($product["CATEGORY_ID"]);
      $oldBrand = BranchService::getById($product["BRANCH_ID"]);
      $oldImages = json_decode($product["DETAIL"], true)["images"]["items"];
      $specs = getSpecs();

      $oldDir = getDir($oldCategory["URL"], $oldBrand["URL"] ?? "tat-ca");
      $newDir = getDir($category["URL"], $brand["URL"]);
      $updatedImg = updateOldImages($oldImages, $oldDir, $newDir, $url);
      $images = array_merge($updatedImg, getUploadedImages($url, count($updatedImg), $newDir));

      $ok = (count($images) > 0); 
      $ok || array_push($errors, "Image is required");
      
      if ($ok){
        $detail = generateDetail($images, $specs, $_POST["description"]);
      
        echo "<pre>";
        print_r($detail);
        echo "</pre>";

        $ok = $ok && ProductService::update([
          "id" => $id,
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

      echo "<h1>Update product ".$id. ": "; var_dump($ok); echo "</h1>";

      echo "<h3 style='color: red'>Errors: ";
      var_dump($errors);
      echo "</h3>";

      if (!$ok) {
        array_push($errors, 'Update failed, please try again!');
        $_SESSION['update_product_error'] = $errors;
        Redirect($_SESSION['last_url']);
      }
      else {
        $_SESSION['rs_message'] = CreateRsMessage(true, "Update product '$name' success");
        Redirect('./Products.php');
      }
    }
  }
?> 