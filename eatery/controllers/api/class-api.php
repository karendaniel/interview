<?php
namespace EATERY;

use EATERY\EAT_Login as login;
use EATERY\EAT_Listing as listing;

class EAT_Api extends  EAT_Database
{
    private $login = null;
    private $listing = null;

    public function __construct()
    {
        $this->login = new login();
        $this->listing = new listing();
    }

 
 /*start*/

  public function login()
  {
     $this->login->login();
  }

  public function createLogin()
  {
    $this->login->createLogin();
  }

  public function listing()
  {
    $this->listing->listing();
  }

  public function createDefaultListing()
  {
    $this->listing->createDefaultListing();
  }

  public function update()
  {
    $this->listing->update();     
  }
    
}

