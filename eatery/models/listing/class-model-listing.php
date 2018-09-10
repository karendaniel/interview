<?php
namespace EATERY;

class EAT_Model_Listing extends EAT_Database
{
  private $database = null;

  public function __construct()
  { 
    
  }

  public function getListing($userID = false)
  {
    if ($userID) {
      $query = "SELECT * FROM listing WHERE user_id = :user_id";
    } else {
       $query = "SELECT * FROM listing";
    }
   
    $stmt = $this->connect()->prepare($query);
    $stmt->execute(array(
      ':user_id'=>$userID
    ));
    $arr = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    if(!$arr) exit('No rows');

    return $arr;
  }

  private function insertListing($data)
  {
    // prepare sql and bind parameters
    foreach ($data as $value) {

      $listName = $value['listName'];
      $distance = $value['distance'];
      $userID = $value['userID'];

      $stmt = $this->connect()->prepare("INSERT INTO listing (list_name, distance, user_id) 
      VALUES ('".$listName."', '".$distance."', ".$userID.")");
      $stmt->execute();

      $stmt = null;
    }
    exit;
  }
  
  /*this can only happen if there's data in table user*/
  public function createDefaultListing()
  {
   
    $data = array(
      array(
      'listName'=>'Pantai Seafood Restaurant',
      'distance'=>'1.9',
      'userID'=>1),
      array(
      'listName'=>'Signature By The Hill @ the Roof',
      'distance'=>'2.4',
      'userID'=>1),
       array(
      'listName'=>'Cinnamon Coffee House',
      'distance'=>'2.6',
      'userID'=>2),
      array(
      'listName'=>'Village Park Restaurant',
      'distance'=>'3',
      'userID'=>2),
      array(
      'listName'=>'Ticklish Park Restaurant',
      'distance'=>'4.2',
      'userID'=>1),
       array(
      'listName'=>'myBurgerLab Sunway',
      'distance'=>'7.7',
      'userID'=>1),
      array(
      'listName'=>'the BULB COFFEE',
      'distance'=>'2.4',
      'userID'=>2),
      array(
      'listName'=>'pappaRich',
      'distance'=>'2.5',
      'userID'=>1),
    );
   
  $this->insertListing($data);
  exit;
  }

  public function updateListing($listingID, $listingName)
  {
    $stmt = $this->connect()->prepare("UPDATE listing SET list_name = :list_name WHERE id = :id")
    ->execute(
     array(
      ':list_name'=>$listingName,
      ':id'=> $listingID));
    $stmt = null;
  }

  public function deleteListing($listingID)
  {
    $stmt = $this->connect()->prepare("DELETE FROM listing  WHERE id = :id")
    ->execute(
     array(
      ':id'=> $listingID));
    $stmt = null;
  }

 public function addListing($userID, $listName, $distance)
 {
    $stmt = $this->connect()->prepare("INSERT INTO listing (list_name, distance, user_id) 
    VALUES ('".$listName."', '".$distance."', ".$userID.")");
    $stmt->execute();

    $stmt = null;
  }
  
}