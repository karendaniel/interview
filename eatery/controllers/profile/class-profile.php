<?php
namespace EATERY;

use EATERY\EAT_Helper as helper;
use EATERY\EAT_User as user;
use EATERY\EAT_Model_userProfile as modelProfile;
use EATERY\EAT_Email as email;

class EAT_Profile extends EAT_Setup
{

  private $response = null;
  private $modelUser = null;
  private $modelProfile = null;

  public function __construct()
  { 
  	$this->response = new \stdClass();
	  $this->user = new user();
    $this->modelProfile = new modelProfile();
	
  }


  public function checkForgotPasswordRequest($usernameEmail, $password)
  {
    return $this->modelProfile->checkForgotPasswordRequest($usernameEmail, $password);
  }
   /**
  * @param int user_id
  * @param string old_password
  * @param string password
  * @param string c_password
  */
  public function changePassword()
  {
      if ($this->user->verifyUserID($_POST['user_id'])->status) {
          
          $userID = $_POST['user_id'];
  
          if (empty($_POST['old_password'])) {

            helper::response('error', 'Current Password required', 400, 0);
          }
    
            $user = get_user_by( 'id', $_POST['user_id'] );

            if ( $user && wp_check_password($_POST['old_password'], $user->data->user_pass, $user->ID) ) {
       
              if ($this->user->verifyPassword($_POST['password'], $_POST['c_password'])->status) {
                   
                  $password = $_POST['password'];

                  wp_set_password($password, $userID);
                  
                  email::sendChangePasswordEmail($user->data->user_email, $user->data->user_nicename);

                  $data = array(
                    'email'=>$user->data->user_email,
                    'nicename'=>$user->data->user_nicename
                  );
                  
                  return helper::response('success', 'Password changed successfully', 200, 0, $data);
              }

          } else {

              helper::response('error', 'Current Password incorrect', 400, 0);
          }
          
      } 

  }


   public function forgotPassword()
   {
      $email = $_POST['user_login'];

      if (strpos($email, '@') !== false) {

        if ($this->user->verifyEmail($email, 1)->status) {

             $user = get_user_by('email', $email);
             $name = $user->data->user_nicename;
          
             $key = wp_generate_password(20, false);
      
             $now = date('Y-m-d H:i:s');

             $userID = $user->data->ID;

             $this->modelProfile->updateForgotPasswordRequest($key, $now, $userID);

             email::sendForgotPasswordEmail($email);

              $data = array(
                'userid'=>$userID
              );

              return helper::response('success', "New password sent to $email", 200, 0, $data);
        }

      } else {
          
          helper::response('error', 'Invalid Email', 400, 0);
      }

  }


public function verifyProfileUpdated()
{
     if ($this->user->verifyUserID($_POST['user_id'])->status) {
          
          $userID = $_POST['user_id'];

          $data = $this->user->getUserData($userID, true)->profile;

          if (!$data) {
     
              helper::response('error', 'Kindly Update your profile', 400, 0);

          }

          return helper::response('success', 0, 0, 1);
          

      }
  }


  /**
     * @since 1.0
     * 
     * updates first, last name and shipping address for mobile users and only for the first time
     * 
     * @param int user_id
     * @param string first_name
     * @param string last_name,
     * @param string street
     * @param string city
     * @param string postcode
     * @param string state
     * 
     * @return true if all ok else error object
     * **/
     
    public function updateProfile()
    {
      //verify userID
      if ($this->user->verifyUserID($_POST['user_id'])->status) {
           
          $userID = (int)$_POST['user_id'];

          $firstName = get_user_meta($userID, 'billing_first_name', true);
          $lastName = get_user_meta($userID, 'billing_last_name', true);
          $address = get_user_meta($userID, 'billing_address_2', true);

          if (!$firstName || !$lastName) {
            $user = $this->verifyFirstLastName($_POST['first_name'], $_POST['last_name']);
              if ($user->status) {

                    $address = $this->verifyAddress($_POST['street'], $_POST['city'], $_POST['state'], $_POST['postcode']);
                    if ($address->status) {
                        
                        $address = $this->verifyAddress($_POST['street'], $_POST['city'], $_POST['state'], $_POST['postcode']);
                        //update now
                        update_user_meta($userID, 'billing_first_name', $user->firstname);
                        update_user_meta($userID, 'billing_last_name', $user->lastname);
                        $fullname = $firstName.' '.$lastName;
                        update_user_meta($userID, 'full_name', $fullname);
                        update_user_meta($userID, 'billing_address_2', $address->street);
                        update_user_meta($userID, 'billing_city', $address->city);
                        update_user_meta($userID, 'billing_state', $address->state);
                        update_user_meta($userID, 'billing_postcode', $address->postcode);
                        update_user_meta($userID, 'billing_country', $address->country);
                        
                      return helper::response('success', 'Profile updated',200, 0);

                    }
                    
               
              }
  
          } else if (isset($_POST['street']) || isset($_POST['city']) || isset($_POST['state']) || isset($_POST['postcode'])) {
              
                if($_POST['first_name'] != $firstName || $_POST['last_name'] != $lastName) {
               
                   helper::response('error', 'Profile cannot be updated. Contact customer service', 400, 0);

                }
                
            //can update address multiple time
            
                if ($this->verifyAddress($_POST['street'], $_POST['city'], $_POST['state'], $_POST['postcode'], $_POST['edit'])->status) {
                    
                    $address = $this->verifyAddress($_POST['street'], $_POST['city'], $_POST['state'], $_POST['postcode'], $_POST['edit']);
                    //update now
                    update_user_meta($userID, 'billing_address_2', $address->street);
                    update_user_meta($userID, 'billing_city', $address->city);
                    update_user_meta($userID, 'billing_state', $address->state);
                    update_user_meta($userID, 'billing_postcode', $address->postcode);
                    update_user_meta($userID, 'billing_country', $address->country);

                    return helper::response('success', 'Address updated',200, 0);

                        
                }
              
            }

      } 
    }



     /**
     * @since 1.0
     * checks for empty and existing username during registration
     * 
     * @param string username
     * 
     * @return true if all ok else error object
     * */
    private function verifyFirstLastName($firstname, $lastname)
    {
        if (empty($firstname)) {
            
             helper::response('error', 'First name required', 400, 0);
        }
        
        if (empty($lastname)) {

             helper::response('error', 'Last name required', 400, 0);
        }
        
        //check if it contains digit
        if(1 === preg_match('~[0-9]~', $firstname) || 1 === preg_match('~[0-9]~', $lastname)){
            
             helper::response('error', 'Only characters allowed for firstname & lastname', 400, 0);

        }
        
        $fullname = $firstname.' '.$lastname;

        $data = array(
          'firstname'=>$firstname,
          'lastname'=> $lastname,
          'fullname'=>$fullname
        );

        return helper::response('success', 0, 0, 1, $data);
    }

    private function verifyAddress($street, $city, $state, $postcode)
    {

        if (empty($street)) {

             helper::response('error', 'Shipping address required', 400, 0);

        }
        if (empty($city)) {
            

            helper::response('error', 'City required', 400, 0);

        } else {
            
            if (strlen($city) < 3) {
                
             helper::response('error', 'City is too short', 400, 0);

           
            }
             //check if it contains digit
            if(1 === preg_match('~[0-9]~', $city)){
                
              helper::response('error', 'Only characters allowed for city', 400, 0);

            } 
        }
        
        
        
        if (empty($state)) {
            
            helper::response('error', 'State required', 400, 0);

        } 
        
        if (empty($postcode)) {
            
            helper::response('error', 'Postcode required', 400, 0);

        } else {

            $postcode = trim($postcode);
            
            if (!ctype_digit($postcode)) {
                
            helper::response('error', 'Only digits are allowed for postcode', 400, 0);

            }
        }

        $data = array(
          'street_number'=>strip_tags($street_number),
          'street'=> strip_tags($street),
          'city'=>strip_tags($city),
          'state'=>strip_tags($state),
          'postcode'=> strip_tags($postcode),
          'country'=>'Malaysia'
        );
        return helper::response('success', 0, 0, 1, $data);

    }
}