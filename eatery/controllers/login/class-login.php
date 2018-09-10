<?php
namespace EATERY;

use EATERY\EAT_Helper as helper;
use EATERY\EAT_User as user;
use EATERY\EAT_Model_Login as modelLogin;

class EAT_Login extends EAT_Setup
{
  private $user = null;
  private $profile = null;
  private $userRole = null;
  private $modelLogin = null;

  public function __construct()
  { 
  	$this->user = new user();
    $this->userRole = array(
      'admin'=>'a',
      'normal'=>'u'
    );
    $this->modelLogin = new modelLogin();
  }

   /**
     * @since 1.0
     * 
     * checks user given credentials and authenticate the user
     * 
     * @param string username
     * @param string password
     * 
     * @return array if true else error object
     * */
  public function login()
  {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if(!$email) {
         helper::response('error', 'email required', 400, 0);
    }

    if(!$password) {
         helper::response('error', 'password required', 400, 0);
    }
  	
    $user = $this->modelLogin->checkUser($email);

    if (password_verify($password, $user['hash'])) {

      $token = trim($this->RandomString());
      $this->modelLogin->updateToken($token, $user['id']);

      return helper::response('success', "Access granted", 200, 0, array(
        'token'=>$token,
        'id'=>$user['id']
      ));

    } else {

         helper::response('error', 'Invalid password', 400, 0);

    }

    exit;
  }

  public function createLogin($email = false, $password = false, $isInternal = false)
  {
    if (!$email && !$password) {

      $email = htmlspecialchars($_POST['email']);
      $password = htmlspecialchars($_POST['password']);
      $role = htmlspecialchars($_POST['role']);
    }
    
    if (!$role) {
      if ($isInternal == 1) {
        $role = 'admin';
      } else {
        $role = 'normal';
      }
    }

    if(!$email) {
         helper::response('error', 'email required', 400, 0);
    }

    if(!$password) {
         helper::response('error', 'password required', 400, 0);
    }

    if(!$role) {
         helper::response('error', 'user role required', 400, 0);
    }

    if (!in_array($role, array_keys($this->userRole))) {
      
         helper::response('error', 'Invalid user role given', 400, 0);

    } 
    //hash password
    $options = [
      'cost' => 11
    ];
    $hash =  password_hash($password, PASSWORD_BCRYPT, $options);
    $type = $this->userRole[$role];
    $this->modelLogin->createUser($email, $hash, $type);

    if (!$isInternal) {
      return helper::response('success', "$role user created successfully", 200, 0);
    }
    
  }


    /**
   * function to generate random strings
   * @param     int   $length   number of characters in the generated string
   * @return    string  a new string is created with random characters of the desired length
   */
   public function RandomString($length = 32) {

      $randstr = ' ';
      srand(mktime());

      $chars = array(
          'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
          'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
          '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 
          'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

      for ($rand = 0; $rand < $length; $rand++) {
          $random = rand(0, count($chars) - 1);
          $randstr .= $chars[$random];
      }
      return $randstr;
  }

  public function authenticateToken($token)
  {
    $isVerified = $this->modelLogin->verifyToken($token);

    if (!$isVerified) {
         helper::response('error', 'Invalid token', 400, 0);
    }
  }

}