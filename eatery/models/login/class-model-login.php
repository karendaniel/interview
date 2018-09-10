<?php
namespace EATERY;

class EAT_Model_Login extends EAT_Database
{
  private $database = null;

  public function __construct()
  { 
    
  }

  public function createUser($email, $hash, $type)
  {

    // prepare sql and bind parameters
    $stmt = $this->connect()->prepare("INSERT INTO user (email, encrypted_password, type) 
    VALUES (:email, :encrypted_password, :type)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':encrypted_password', $hash);
    $stmt->bindParam(':type', $type);
    $stmt->execute();

  }

  public function checkUser($email)
  {
    $query = "SELECT encrypted_password as hash, id FROM user WHERE email = :email";
    $stmt = $this->connect()->prepare($query);
    $stmt->execute(array(
      ':email'=>$email
    ));
    $arr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    if(!$arr) exit('No rows');

    return $arr[0];
  }

  public function updateToken($token, $userID)
  {
    $stmt = $this->connect()->prepare("UPDATE user SET token = :token WHERE id = :id")
    ->execute(array(
      ':token'=>$token,
      ':id'=>$userID
    ));
  }

  public function verifyToken($token)
  {
    $query = "SELECT COUNT(*) as count FROM user WHERE token = :token";

    $stmt = $this->connect()->prepare($query);
    $stmt->execute(array(
      ':token'=>$token
    ));
    $arr = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    if(!$arr) exit('No rows');

    return $arr[0]['count'];
  }
}