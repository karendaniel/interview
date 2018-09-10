<?php
namespace EATERY;

class EAT_Model_userProfile extends EAT_Database
{
  private $database = null;

  public function __construct()
  { 
  	
  }

  public function checkForgotPasswordRequest($usernameEmail, $password)
   {
        if (strpos($usernameEmail, '@') !== false) {
            //its an email
            $query = "SELECT ID, fg_password FROM wp_users WHERE user_email = '$usernameEmail' AND fg_request_time >= ( CURDATE() - INTERVAL 2 DAY )";
        } else {
            //its username
            $query = "SELECT ID, fg_password FROM wp_users WHERE user_login = '$usernameEmail' AND fg_request_time >= ( CURDATE() - INTERVAL 2 DAY )";
        }

        $result = $this->wdb()->get_results($query)[0];

       if ($result->fg_password != '' && $password == $result->fg_password) {

           wp_set_password($result->fg_password, $result->ID );
           
           //after updated, delete it
           $this->wdb()->update( 
              'wp_users', 
              array( 
                'fg_password' => ''
              ), 
              array( 'ID' => $result->ID ), 
              array( 
                '%s',
              ), 
              array( '%d' ) 
            );
       } 
    }


    public function updateForgotPasswordRequest($password, $now, $userID)
    {
       $this->wdb()->update( 
            'wp_users', 
            array( 
              'fg_password'=>$password,
              'fg_request_time'=>$now
            ), 
            array('ID' => $userID), 
            array( 
              '%s',
              '%s'
            ), 
            array('%d') 
          );
    }
  
}