<?php
  class UserService {

    public static function CheckUsernameExist(string $username){
      return self::GetUserInfo($username) != null;
    }

    public static function Add($user){
      $sql = SqlBuilder::from('users(username, name, password, phone, address)')
        ->insert('?,?,?,?,?')
        ->build();

      $password = password_hash($user['password'], PASSWORD_DEFAULT);

      $rs = Provider::query($sql, 'sssss', [$user['username'], $user['name'], $password, $user['phone'], $user['address']], true);

      if ($rs) {
        return Self::GenerateToken($user['username']);
      }
      
      return false;
    }

    public static function Update($user){
      $updateStr = 'name=?,phone=?,address=?';
      $paramStr = 'ssss';
      $params = [$user['name'], $user['phone'], $user['address']];

      if (Util::isKeyExists('password', $user) && $user['password'] !== ''){
        $updateStr .= ',password=?';
        $paramStr .= 's';
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        array_push($params, $password);
      }

      array_push($params, $user['username']);

      $sql = SqlBuilder::from('users')
        ->update($updateStr)
        ->where('username=?')
        ->build();

      return Provider::query($sql, $paramStr, $params, true);
    }

    public static function GenerateToken($username){
      $token = [
        "username" => $username
      ];

      return JWT::encode($token, Config::getValue('token_secret_key'));
    }

    public static function Login($input){
      $userInfo = self::GetUserInfo($input['username']);

      if ($userInfo != null && password_verify($input['password'], $userInfo['PASSWORD'])){
        return self::GenerateToken($userInfo['USERNAME']);
      }

      return false;
    }

    public static function Authen(){
      $headers = getallheaders();
      if (array_key_exists('Authorization', $headers)) {
          $jwt = $headers['Authorization'];
          $token = JWT::decode($jwt, Config::getValue('token_secret_key'));
          //print_r($token);

          return $token->username;
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

    public static function GetAll($page = 1){
      $sql = SqlBuilder::from('users')
        ->select();
      
      return Provider::paginate($sql, $page, 20);
    }
  }
?>