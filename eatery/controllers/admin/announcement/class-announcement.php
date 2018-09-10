<?php
namespace DOROPU;

use DOROPU\DROP_View as view;

class DROP_Announcement extends DROP_Setup
{

  public function __construct()
  { 
  }

  public  function announcement()
  {
      
    //   $this->obj->title = 'announcement';
    //   $this->obj->image = 'https://www.doropu.com/api/wp-content/uploads/2018/07/popup.png';
    //   $this->obj->date_show_from = '2018-07-30';
    //   $this->obj->date_show_to = '2018-08-07';
      
         
    //   $executionTime = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

      $this->obj->status = false;
      $this->obj->message = 'NO announcement';
    //   $this->obj->seconds = $executionTime;


       header('Access-Control-Allow-Origin: *');
       header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
       header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, viewerTZOffset');
			
       echo json_encode($this->obj);
       exit;
  }
  
}