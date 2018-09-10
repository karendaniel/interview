<?php
namespace EATERY;

class EAT_Model_User extends EAT_Database
{
  private $database = null;

  public function __construct()
  { 
    
  }

  public function verifyPhone($phone)
  {
     $query = "SELECT COUNT(*) FROM wp_usermeta WHERE meta_key = 'billing_phone' AND meta_value = '".$phone."'";

     $result = $this->wdb()->get_var($query);
     return $result;
  }

  public function getAvailableFund($userID)
  {
     $query = "SELECT meta_value FROM wp_usermeta  WHERE meta_key ='account_funds' AND user_id = $userID";

     $result = $this->wdb()->get_var($query);
     
     return $result;
  }


 public static function getAvailableFund2($userID)
  {
     $query = "SELECT meta_value FROM wp_usermeta  WHERE meta_key ='account_funds' AND user_id = $userID";

     $result = self::staticWdb()->get_var($query);
     
     return $result;
  }

  public function deductAccountFund($userID, $currentFund)
  {
    $query = "SELECT 
        (SELECT meta_value FROM wp_usermeta WHERE meta_key = 'account_funds' AND user_id = u.ID) as available_fund 
        FROM wp_users u WHERE u.ID = $userID";
     $availableFund = (float)$this->wdb()->get_var($query);
     
     $availableFund -= $currentFund;

      //update the fund
      $this->wdb()->update( 
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