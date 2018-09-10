<?php
namespace DOROPU;

use DOROPU\DROP_Helper as helper;

class DROP_Points extends DROP_Setup
{
  public function __construct()
  {

  }

  public function getPointsLog()
  {
        
      $result = r\table("pointRedemption")->orderBy(r\desc('date_time'))->run($this->setup->rConnect());
        $points = array();
        foreach($result as $r) {
           $points[] = $r;
        }
        echo json_encode($points);
        exit;
  }

  public function awardedTopup()
  {
      if ($this->verifyUsername($_POST['username'], 1)->status) {
          
          $username = $_POST['username'];
          
          $user = get_userdatabylogin($username );
          $userID = $user->data->ID;
      
          $productID = 1964;
          $amount = (float)$_POST['amount'];
          $minAmount = 0.01;
          
         $given_by_id = (int)$_POST['given_by_id'];
         $given_by = $_POST['given_by'];
         $purpose = $_POST['purpose'];
         
        if ($amount >= (float)$minAmount) {

              $firstName = get_user_meta($userID, 'billing_first_name', true);
         
              $lastName = get_user_meta($userID, 'billing_last_name', true);
              
              $email = get_user_meta($userID, 'billing_email', true);
              $phone  = get_user_meta($userID, 'billing_phone', true);
              $street = get_user_meta($userID, 'billing_address_1', true);
              $city = get_user_meta($userID, 'billing_city', true);
              $state = get_user_meta($userID, 'billing_state', true);
              $postcode = get_user_meta($userID, 'billing_postcode', true);
              $country = get_user_meta($userID, 'billing_country', true);
              
              global $woocommerce;
    
              $address = array(
                  'first_name' => $firstName,
                  'last_name'  => $lastName,
                  'email'      => $email,
                  'phone'      => $phone,
                  'street'  => $street,
                  'city'       => $city,
                  'state'      => $state,
                  'postcode'   => $postcode,
                  'country'    => $country
              );
       
        $order = wc_create_order(array('customer_id' => $userID));
        $order->add_product( get_product($productID ), $amount ); //(get_product with id and next is for quantity)
        $order->set_address( $address, 'billing' );
        $order->set_address( $address, 'shipping' );
        $order->set_total($amount);
        
    



    wc_add_order_item_meta($item_id, '_top_up_amount', $amount, true);

//     // wc_add_order_item_meta($item_id, '_line_subtotal', $amount);

    wc_add_order_item_meta($item_id, '_top_up_product', 'yes', true);

//   }
	
	//end
        $order->calculate_totals();
        
    update_post_meta( $order->data['id'], '_payment_method', 'rewarded_free' );
    update_post_meta($order->data['id'], '_payment_method_title', 'FREE' );
    $order->update_status("reward", 'Reward', TRUE);
         $today = date("Y-m-d H:i:s");
            //   $availableFund = $this->availableFund($userID, 1);
            
              if (sizeof($order) > 0) {
                  
                    $this->addPoints($userID, $amount, $purpose, $given_by_id, $given_by);
                    $this->sendRewardEmail($email, $amount, $order->data['id']);
        
        
                  $this->obj->status = true;
                  $this->obj->message = 'Order success.';
                  $this->obj->data = $order->data;

                  header('Access-Control-Allow-Origin: *');
                  header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
                  header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, viewerTZOffset');
                  echo json_encode($this->obj);
                  exit;
              }else {
                  $validation = new stdClass();
                  $validation->status = false;
                  $validation->message = 'Unable to create order';
                  
                  
                 $this->obj->status = false;
                 $this->obj->message = 'Order failed.';
                 $this->obj->error = $validation;
              }

              header('Access-Control-Allow-Origin: *');
              header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
              header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, viewerTZOffset');
              echo json_encode($this->obj);
              exit;

          } else {
              $this->obj->status = false;
              $this->obj->message = 'Kindly Top up minimum 10';
              header('Access-Control-Allow-Origin: *');
              header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
              header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, viewerTZOffset');
              echo json_encode($this->obj);
              exit;
      } 

             
      }
       $this->obj->status = false;
      $this->obj->message = 'Invalid Username';
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
      header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, viewerTZOffset');
      echo json_encode($this->obj);
      exit;
  }


   public function addPoints($userID, $amount, $purpose, $givenByID, $givenBy)
  {

       if (empty($userID)) {
            
           echo json_encode('userID required');
           wp_die();
        }
        
        $user = get_userData($userID);

        if (!$user) {
            echo json_encode('Invalid user ID');
            wp_die();
        }

        //add money in
        $userID = (int)$userID;
        $currentFund = (float)$amount;
        $beforeModify = (float)0;
        $availableFund = (float)0;
        $availableFund += $amount;

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
            		'meta_value' => $amount
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
         $beforeModify = (float)$this->db->get_var($query);
         $availableFund = (float)$beforeModify;
         
         $availableFund += $amount;
   
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
     
     
        //update log
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $date = date("Y-m-d H:i:s");


        $document = array(
        "user_id"=> $userID,
        "fund"=> $amount,
        "purpose"=> $purpose,
        "given_by"=> $givenBy,
        "given_by_id"=> $givenByID,
        "date_time"=>$date,
        "before_fund"=>$beforeModify,
        "after_fund"=>$availableFund
        );
        
         $result = r\table("pointRedemption")->insert($document)
        ->run($this->setup->rConnect());
        
        $this->setup->rConnect()->close();
        
  }
}