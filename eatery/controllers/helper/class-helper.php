<?php
namespace EATERY;

class EAT_Helper extends EAT_Setup
{
  public static $response = null;

  public function __construct()
  { 
  }

  public static function renderHeader()
  {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, viewerTZOffset');
  }


  public static function response($type, $message = false, $code = false, $isInternal = false, $data = false)
  {

  	if (self::$response == null) {
		  self::$response = new \stdClass();
  	}

  	$status = false;
  	if ($type == 'success') {
  		  $status = true;
  	}

  	self::$response->status = array(
      'code'=>$code,
      'message'=>$message
    );

    if($data) {
      if (is_array($data)) {
        foreach($data as $k=>$d) {

          self::$response->{$k} = $d;
        }
      } else {

        self::$response = $data;
      }
      
    }

    self::renderHeader();
    if ($isInternal) {

    	return self::$response;
    }

    echo json_encode(self::$response);
    exit;
    
  }

  public static function formatPhoneNumber($phoneNumber)
  {
    $countrycode = substr($phoneNumber, 0, 1);
    //default country code is 6 = Malaysia
    if($countrycode != 6) {
        $phone = '6'.$phoneNumber;
    }

    return $phone;
  }
}