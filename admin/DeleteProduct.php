<?php 
  require_once "../server/index.php";
  require_once "./Authen.php";

  if (!empty($_GET) && isset($_GET['url'])){
    $url = $_GET['url'];
    $product = ProductService::getByUrl($url);
    $brand_id = $product["BRANCH_ID"];
    $category_id = $product["CATEGORY_ID"];
    $bUrl = isset($brand_id) ? BranchService::getById($brand_id)["URL"] : "tat-ca";
    $cUrl= CategoryService::getById($category_id)["URL"];

    $images = json_decode($product["DETAIL"], true)["images"]["items"];

    foreach ($images as $key => $img){
      $imgUrl = Config::getValue('client_images')."/details/$cUrl/$bUrl/$img";
      unlink($imgUrl);
    }

    $isOk = ProductService::delete($url);
    $_SESSION['rs_message'] = CreateRsMessage($isOk, "Delete product $url ". ($isOk ? 'success' : 'fail'));
    Redirect($_SESSION['last_url']);
  }
?>