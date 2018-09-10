<?php
namespace EATERY;

use EATERY\EAT_View as view;

class EAT_Email extends EAT_Setup
{

  public function __construct()
  { 
  }

    
    private static function getNameByEmail($email)
    {
        $user = get_user_by('email', $email);
        return $user->data->user_nicename;
    }

    public static function sendRegistrationEmail($email)
    {

        view::emailTemplate('register_email', 'Registration Successful', array(
          'email'=>$email,
          'name'=>self::getNameByEmail($email)
        ));
      
    }

    
    public static function sendChangePasswordEmail($email, $name)
    {
        view::emailTemplate('change_password', 'Password Changed', array(
          'email'=>$email,
          'name'=>$name
        ));
      
    }

    public static function sendForgotPasswordEmail($email)
    {
        view::emailTemplate('forgot_password', 'Reset Password', array(
          'email'=>$email,
          'name'=>self::getNameByEmail($email)
        ));
      
    }

    public function sendRewardEmail($email, $amount, $orderID)
    {          
        view::emailTemplate('reward_email', 'Reward Successful', array(
          'email'=>$email,
          'amount'=>$amount,
          'name'=>self::getNameByEmail($email)
        ));
    }

    
}