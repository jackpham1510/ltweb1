<?php 
  require_once "provider/Provider.php";
  require_once "core/Util.php";
  require_once "core/Config.php";

  class UserService {

    public static function CheckUsernameExist(string $username){
      return self::GetUserInfo($username) != null;
    }

    public static function Add($user){
      if (self::Validate($user)){
        $sql = SqlBuilder::from('users(username, name, password, phone, address)')
          ->insert('?,?,?,?,?')
          ->build();

        $password = password_hash($user['password'], PASSWORD_DEFAULT);

        $rs = Provider::query($sql, 'sssss', [$user['username'], $user['name'], $password, $user['phone'], $user['address']], true);

        if ($rs) {
          return Self::GenerateToken($user['username']);
        }
      }
      
      return false;
    }

    public static function Validate($user){
      if (strlen($user['username']) < 4 || strlen($user['password']) < 6 || strlen($user['phone']) < 9 || strlen($user['address']) < 1){
        return false;
      }
      return true;
    }

    public static function GenerateToken($username){
      $token = [
        "username" => $username
      ];

      return JWT::encode(["token" => $token], Config::getValue('token_secret_key'));
    }

    public static function Login($input){
      $userInfo = self::GetUserInfo($input['username']);

      if ($userInfo != null && password_verify($input['password'], $userInfo['PASSWORD'])){
        return self::GenerateToken($userInfo['USERNAME']);
      }

      return false;
    }

    public static function Authen($user){
      $headers = getallheaders();
      if (array_key_exists('Authorization', $headers)) {
          $jwt = $headers['Authorization'];
          $token = JWT::decode($jwt, Config::getValue('token_secret_key'));
          
          return $token['username'];
      }

      return false;
    }

    public static function GetUserInfo($username){
      $sql = SqlBuilder::from('users')
        ->select()
        ->where('username=?')
        ->build();
      
      return Provider::select($sql, 's', [$username])[0];
    }
  }
?>