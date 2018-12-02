<?php 
  require_once "../server/index.php";
  require_once "Helper.php";

  session_start();

  class AdminAuthen {
    private static $user;
    public static function verify(){
      if (isset($_SESSION['username'])){
        self::$user = UserService::GetUserInfo($_SESSION['username']);
      }
      else {
        header('Location: '. Config::getValue('admin_base'));
      }
    }
    public static function getUser(){
      return $user;
    }
    public static function logout(){
      if (isset($_SESSION['username'])){
        unset($_SESSION['username']);
        unset($_SESSION['login_error']);
      }
      Redirect('/');
    }
  }

  AdminAuthen::verify();
?>