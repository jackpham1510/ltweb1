<?php 
  require_once "../server/index.php";

  function Redirect(string $path){
    header("Location: ".Config::getValue("admin_base").$path);
  }

  function GetPath() {
    return substr($_SERVER["REQUEST_URI"], strrpos($_SERVER["REQUEST_URI"], "/"));
  }

  function CreateRsMessage($success, $message){
    return [
      "success" => $success,
      "message" => $message
    ];
  }

  function GetFileType($file){
    return strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
  }

  function FilterFileType($file, $acceptTypes) {
    $fileType = GetFileType($file);
    foreach($acceptTypes as $k => $v){
      if ($fileType === $v){
        return true;
      }
    }
    return false;
  }

  function is_file_ok($file){
    return $file["error"] === 0 && getimagesize($file["tmp_name"]) !== 0;
  }

  function SaveFile($file, $dir, $filename = null, $force_type = null){
    $filename = $dir.'/'.(isset($filename) ? ($filename . ($force_type ?? "")) : basename($file['name']));
    return move_uploaded_file($file["tmp_name"], $filename);
  }

  function MakeUrl($s){
    return strtolower(str_replace(" ", "-", trim($s)));
  }
?>