<?php
namespace EATERY;

use EATERY\EAT_Helper as helper;

class EAT_View extends EAT_Setup
{

  private $response = null;

  public function __construct()
  { 
	
  }

  public static function template($filename, $title, $data = false)
  {
      $file = FILE_PATH.'/views/listing/'.$filename.'.php';
      require_once($file);
  }

}