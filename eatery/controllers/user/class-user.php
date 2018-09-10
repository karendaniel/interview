<?php
namespace EATERY;

use EATERY\EAT_Helper as helper;
use EATERY\EAT_Model_User as modelUser;
use EATERY\EAT_Product as product;


class EAT_User extends EAT_Setup
{

  private $response = null;
  private $modelUser = null;
  private $product = null;


  public function __construct()
  { 
  	$this->response = new \stdClass();
	  $this->modelUser = new modelUser();
  }

  /**
     * @since 1.0
     * checks for empty and existing username during registration
     * 
     * @param string username
     * 
     * @return true if all ok else error object
     * */
    public function verifyUsername($username, $isLogin = false)
    {
         //check if username given
        if (empty($username)) {

            helper::response('error', 'Username required', 400, 0);
        }
        
        if (!$isLogin) {

            if(!preg_match('/^[\w]+$/', $username)){

                helper::response('error', 'Only characters and numbers and underscore allowed for username', 400, 0);
            } 
             //check if it contains min 5 characters
            if (strlen($username) < 5) {
                
                helper::response('error', 'Username has to be minimum 5 character in length', 400, 0);
            }
            
            if ($username == trim($username) && strpos($username, ' ') !== false) {
                
                helper::response('error', 'Space not allowed in username', 400, 0);
            }
        }
   
       if (strpos($username, '@') !== false) {
  
            if (email_exists($username)) {
        
                 if (!$isLogin) {

                    helper::response('error', 'Email exist', 400, 0);
                 } 

                 return helper::response('success', 0, 0, 1);
               
              } 
        } else {
             //check if username exist
              if (username_exists($username)) {
                 if (!$isLogin) {

                    helper::response('error', 'Username exist', 400, 0);
                 } 
                 
                  return helper::response('success', 0, 0, 1);
              } 
        }
        
       
      if (!$isLogin) {

          return helper::response('success', 0, 0, 1);
      }

        helper::response('error', 'Username /email doesn\'t exist', 400, 0);
    }




    /**
     * @since 1.0
     * checks for empty and existing email during registration
     * 
     * @param string email
     * 
     * @return true if all ok else error object
     * */
    public function verifyEmail($email, $isForgotPassword = false)
    {
        if (empty($email)) {

            helper::response('error', 'Email required', 400, 0);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
            helper::response('error', 'Invalid email', 400, 0);
        }
        
        if ($isForgotPassword) {

            if (email_exists($email)) {

            	return helper::response('success', 0,0, 1);
            } else {
                helper::response('error', 'Email doesn\'t exist', 400, 0);
            }
        } else {

            if (email_exists($email)) {

                helper::response('error', 'Email already exist', 400, 0);
            } else {

            }
        }

        return helper::response('success', 0,0, 1);
    }


     /**
     * @since 1.0
     * checks for empty and confirm password during registration
     * 
     * @param string password
     * @param string c_password
     * 
     * @return true if all ok else error object
     * */
     
    public function verifyPassword($password, $confirmPassword, $isLogin=false)
    {
        if (empty($password)) {

            helper::response('error', 'Password required', 400, 0);
        }
        
        if (!$isLogin) {
            if (strlen($password) < 8) {

                helper::response('error', 'Minimum 8 characters required for password', 400, 0);
            }
        }
        if (!$isLogin) {
            
          if (empty($confirmPassword)) {
   
            	helper::response('error', 'Confirm Password required', 400, 0);
            }
            
            if ($password != $confirmPassword) {
         
            	helper::response('error', 'Password mismatch', 400, 0);
          }
        }

        return helper::response('success', 0,0, 1);
    }


     /**
     * @since 1.0
     * checks for empty and existing phone no during registration
     * 
     * @param string phone
     * 
     * @return true if all ok else error object
     * */
    public function verifyPhone($phone)
    {
        if (empty($phone)) {

            helper::response('error', 'Phone number required', 400, 0);
        }
        
        $phone = Helper::formatPhoneNumber($phone);

        if ($this->modelUser->verifyPhone($phone) > 0) {

             helper::response('error', 'Phone number exist', 400, 0);
        }

        return helper::response('success', 0,0, 1);
    }


  public function getUserData($userID = false, $isInternal = false)
  {
      $meta = new \stdClass();

      if ($_POST['user_id']) {
          $userID = $_POST['user_id'];
      }

      $userID = (int) $userID;

     if ($this->verifyUserID($userID)->status) {
          
          $user = get_userdata($userID)->data;
          
          $firstName = get_user_meta($userID, 'billing_first_name', true);
         
          $lastName = get_user_meta($userID, 'billing_last_name', true);
                  
          $email = get_user_meta($userID, 'billing_email', true);

          $streetNumber = get_user_meta($userID, 'billing_address_1', true);
          
          $street = get_user_meta($userID, 'billing_address_2', true);

          if (!$firstName || !$lastName || !$email || !$street) {

              $profile = false;
              $meta->phone = get_user_meta($userID, 'billing_phone', true);
              $meta->email = $email;
              
          } else {
              $profile = true;
              
              $meta->first_name = $firstName;
              $meta->last_name = $lastName;
              $meta->street_number = $streetNumber;
              $meta->street = $street;
              $meta->city = get_user_meta($userID, 'billing_city', true);
              $meta->state = get_user_meta($userID, 'billing_state', true);
              $meta->postcode = get_user_meta($userID, 'billing_postcode', true);
              $meta->country = get_user_meta($userID, 'billing_country', true);
              $meta->phone = get_user_meta($userID, 'billing_phone', true);
              $meta->email = $email;
          }
          
          $data = array(
          'data'=>$user,
          'profile'=>$profile
          );
          

          if ($meta) {

              $data['user_meta'] = $meta;
          }

          if ($isInternal) {

              return helper::response('success', 0,0, 1, $data);
          }

         return  helper::response('success', 0,0, 0, $data);

     }
             
  }

  public function verifyUserID($userID)
  {
       if (empty($userID)) {
          helper::response('error', 'userID required', 400, 0);
      }
      
      $user = get_userData($userID);
      if ($user) {

          $data = array(
          'user_id'=>$userID
          );
          
          return helper::response('success', 0,0, 1, $data);

      }

      helper::response('error', 'Invalid user ID', 400, 0);
    }



    /**
  * 
  * @since 1.0
  * 
  * get available fund for given user
  * 
  * @param int user_id
  * 
  * @return float available fund
  * */
  public function availableFund($userID = false, $isInternal = false)
  {
      if (!empty($userID)) {
   
          $userID = $_POST['user_id'];
      }

      if ($this->verifyUserID($_POST['user_id'])->status) {
          
          $userID = (int)$_POST['user_id'];
          
          $result = $this->modelUser->getAvailableFund($userID );

          if ($isInternal) {

              return $result;
          }
              
          if ($result >= 0) {

              $data = array(
                'availableFund'=>($result == null ? '0' : $result)
              );
              return helper::response('success', "fetching available fund success", 200, 0, $data);
          }

          helper::response('error', 'fetching available fund failed', 400, 0);
      }
  
  }

  public function checkSufficientDeposit()
  {
      if ($this->verifyUserID($_POST['user_id'])->status) {
          
          $userID = (int)$_POST['user_id'];

          $this->product = new product();
          
          $availableFund = round($this->availableFund($userID, 1), 2);

          $price = round($this->product->getCurrentProductPrice(), 2);

          if ($availableFund < $price) {

            helper::response('error', 'Your credit is low', 400, 0);

          }
         
          return helper::response('success', 0,0, 1);

      }
  }


  public function getUserAddress($userID)
  {

    $uObject = new \stdClass();

    $uObject->firstName = get_user_meta($userID, 'billing_first_name', true);
         
    $uObject->lastName = get_user_meta($userID, 'billing_last_name', true);
    
    $uObject->email = get_user_meta($userID, 'billing_email', true);
    $uObject->phone  = get_user_meta($userID, 'billing_phone', true);
    $uObject->street = get_user_meta($userID, 'billing_address_2', true);
    $uObject->city = get_user_meta($userID, 'billing_city', true);
    $uObject->state = get_user_meta($userID, 'billing_state', true);
    $uObject->postcode = get_user_meta($userID, 'billing_postcode', true);
    $uObject->country = get_user_meta($userID, 'billing_country', true);


    $address = array(
        'first_name' => $uObject->firstName,
        'last_name'  => $uObject->lastName,
        'email'      => $uObject->email,
        'phone'      => $uObject->phone,
        'street'  => $uObject->street,
        'city'       => $uObject->city,
        'state'      => $uObject->state,
        'postcode'   => $uObject->postcode,
        'country'    => $uObject->country
    );

    return $address;
  }

  public function blockedBuyers($userID)
  {
     $blocked = array(
      1261,
      1293,
      771,
      317,
    //   257,
      686,
      239,
      9838,
      4781,
      362
      );

     if(in_array($userID, $blocked)) {
         helper::response('error', 'You\'re not allowed to purchase. Kindly contact Doropu Customer Service', 400, 0);
     }

    return helper::response('success', 0,0, 1);

  }

  public function deductAccountFund($userID, $currentFund)
  {
      $userID = (int)$userID;
      $currentFund = (float)$currentFund;
      
      $this->modelUser->deductAccountFund($userID, $currentFund);
        
  }

  private function updateAccountFund($userID, $currentFund)
  {
      $userID = (int)$userID;
      $currentFund = (float)$currentFund;

      //check if meta key even exist (for fresh register it wont exist)
      $query = "SELECT 
        (SELECT COUNT(meta_value)FROM wp_usermeta WHERE meta_key = 'account_funds' AND user_id = u.ID) as available_fund 
        FROM wp_users u WHERE u.ID = $userID";
      $count = (float)$this->db->get_var($query);

     if ($count <= 0) {
          $this->db->insert( 
            'wp_usermeta', 
            array( 
                'user_id' => $userID, 
                'meta_key' => 'account_funds',
                'meta_value' => $currentFund
            ), 
            array( 
                '%d', 
                '%s',
                '%s'
            ) 
        );

     } else {
     
      //get available fund
      $query = "SELECT 
        (SELECT meta_value FROM wp_usermeta WHERE meta_key = 'account_funds' AND user_id = u.ID) as available_fund 
        FROM wp_users u WHERE u.ID = $userID";
     $availableFund = (float)$this->db->get_var($query);
     
     $availableFund += $currentFund;

      //update the fund
        $this->db->update( 
            'wp_usermeta', 
            array( 
                'meta_value' => $availableFund
            ), 
            array( 
                'user_id' => $userID,
                'meta_key' =>'account_funds'), 
            array( 
                '%s'
            ), 
            array(
                '%d',
                '%s') 
        );
        
     }

  }

  public function isUsernameEmailExist()
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        
        if (empty($username) && empty($email)) {
            
          helper::response('error', 'Input required', 400, 0);

        }
        
        if (!empty($username) && !empty($email)) {

          helper::response('error', 'check email or username at a time', 400, 0);
        }
        
        
        if (strpos($email, '@') !== false) {
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            
              helper::response('error', 'Invalid email', 400, 0);

            }
        
            if (email_exists($email)) {
        
              helper::response('error', 'Email exist', 400, 0);

              } 
        } else {
             //check if username exist
             
            if(!preg_match('/^[\w]+$/', $username)){
                
              helper::response('error', 'Only characters and numbers and underscore allowed for username', 400, 0);

            } 
             //check if it contains min 5 characters
            if (strlen($username) < 5) {
                
              helper::response('error', 'Username has to be minimum 5 character in length', 400, 0);

            }
            
            if ($username == trim($username) && strpos($username, ' ') !== false) {
                
              helper::response('error', 'Space not allowed in username', 400, 0);

            }


              if (username_exists($username)) {

              helper::response('error', 'Username exist', 400, 0);
               
              } 
        }
        

        return helper::response('success', 'Valid input given',200, 0);

    }

  public static function getUserAvailableBalanceForRedeem($userID)
  {
    
    $fund = DROP_Model_User::getAvailableFund2();

    return (float)$fund;

  }

}