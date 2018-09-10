<?php
namespace EATERY;

use EATERY\EAT_Helper as helper;
use EATERY\EAT_Login as login;
use EATERY\EAT_Model_Listing as modelListing;
use EATERY\EAT_view as view;

class EAT_Listing extends EAT_Setup
{

  private $modelListing = null;
  private $login = null;


  public function __construct()
  { 
    $this->modelListing = new modelListing();
    $this->login = new login();

  }

  public function displayListing()
  {
      view::template('listing', 'LISTING', array(
          'data'=>$this->listing()
        ));
  }

  public function addListing()
  {
    view::template('add_listing', 'LISTING');
  }

  public function handleSubmittedListing()
  {
    $userID = (int)$_POST['user_id'];
    $listName= $_POST['list_name'];
    $distance = $_POST['distance'];

    $this->modelListing->addListing($userID, $listName, $distance);
    // return helper::response('success', "Listing Successfully added", 200, 0);
    wp_redirect( home_url('listing') );
  }

  public function deletelisting()
  {
    $ID = (int)$_POST['id'];
    $this->modelListing->deleteListing($ID);

     return helper::response('success', "Listing Successfully Deleted", 200, 0);
  }
  //sample request url 
  //hhttp://local.interview/index.php/wp-json/eatery/v1/mobile?request=listing&id=2&token=VCQHchbzkZ3bkjKpygYsFOJTuJhpnPKe
  public function listing($ID = false)
  {

    if (!empty($_GET)){
      $token = trim($_GET['token']);
      $id = $_GET['id'];

      $this->login->authenticateToken($token);

      if (!$id) {
         helper::response('error', 'ID required', 400, 0);
      }
    }

    $list  = $this->modelListing->getListing($ID);
    
    if (!empty($_GET)){
      return helper::response('success', "Listing Successfully Retreived", 200, 0, array(
        'listing'=>$list
      ));
    }

    return $list;
  }

  public function createDefaultListing()
  {
    $this->modelListing->createDefaultListing();
  }

  public function update()
  {

    $token = trim($_POST['token']);
    $id = $_POST['id'];
    $listingID = $_POST['listing_id'];
    $listingName = $_POST['listing_name'];

    $this->login->authenticateToken($token);

    // if (!$id) {
    //    helper::response('error', 'ID required', 400, 0);
    // }

    if (!$listingID) {
       helper::response('error', 'Listing ID required', 400, 0);
    }

    if (!$listingName) {
       helper::response('error', 'Listing Name required', 400, 0);
    }

    $this->modelListing->updateListing($listingID, $listingName);

    return helper::response('success', "Listing Successfully Updated", 200, 0);
  }

}