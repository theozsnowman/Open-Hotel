<?php
require __DIR__ . '/config.php';
/*
* Tutorial: PHP Login Registration system
*
* Page: Application library
* */

class AppLib
{

  /*
   * Register New User
   *
   * @param $name, $email, $username, $password
   * @return ID
   * */
  public function Register($full_name,$username, $email,  $password)
  {
      try {
          $db = DataBase();
          $query = $db->prepare("INSERT INTO users(FullName, UserName, Email, Password) VALUES (:fname,:uname,:email,:pass)");
          $query->bindParam("fname", $full_name, PDO::PARAM_STR);
          $query->bindParam("uname", $username, PDO::PARAM_STR);
          $query->bindParam("email", $email, PDO::PARAM_STR);
          $enc_password = password_hash($password,PASSWORD_DEFAULT);
          $query->bindParam("pass", $enc_password, PDO::PARAM_STR);
          $query->execute();
          return $db->lastInsertId();
      } catch (PDOException $e) {
          exit($e->getMessage());
      }
  }

  /*
   * Check Username
   *
   * @param $username
   * @return boolean
   * */
  public function isUsername($username)
  {
      try {
          $db = DataBase();
          $query = $db->prepare("SELECT id FROM users WHERE UserName=:username");
          $query->bindParam("username", $username, PDO::PARAM_STR);
          $query->execute();
          if ($query->rowCount() > 0) {
              return true;
          } else {
              return false;
          }
      } catch (PDOException $e) {
          exit($e->getMessage());
      }
  }

  /*
   * Check Email
   *
   * @param $email
   * @return boolean
   * */
  public function isEmail($email)
  {
      try {
          $db = DataBase();
          $query = $db->prepare("SELECT id FROM users WHERE Email=:email");
          $query->bindParam("email", $email, PDO::PARAM_STR);
          $query->execute();
          if ($query->rowCount() > 0) {
              return true;
          } else {
              return false;
          }
      } catch (PDOException $e) {
          exit($e->getMessage());
      }
  }

  /*
   * Login
   *
   * @param $username, $password
   * @return $mixed
   * */
  public function Login($username, $password)
  {
      try {
          $db = DataBase();
          $query = $db->prepare("SELECT id FROM users WHERE (UserName=:username OR Email=:username) AND Password=:password");
          $query->bindParam("username", $username, PDO::PARAM_STR);
          $enc_password = password_hash( $password,PASSWORD_DEFAULT);
          $query->bindParam("password", $enc_password, PDO::PARAM_STR);
          $query->execute();
          if ($query->rowCount() > 0) {
              $result = $query->fetch(PDO::FETCH_OBJ);
              return $result->user_id;
          } else {
              return false;
          }
      } catch (PDOException $e) {
          exit($e->getMessage());
      }
  }

  /*
   * get User Details
   *
   * @param $user_id
   * @return $mixed
   * */
  public function UserDetails($user_id)
  {
      try {
          $db = DataBase();
          $query = $db->prepare("SELECT id, FullName, UserName, Email FROM users WHERE id=:user_id");
          $query->bindParam("user_id", $user_id, PDO::PARAM_STR);
          $query->execute();
          if ($query->rowCount() > 0) {
              return $query->fetch(PDO::FETCH_OBJ);
          }
      } catch (PDOException $e) {
          exit($e->getMessage());
      }
  }
}

?>
