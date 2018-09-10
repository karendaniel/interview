<?php
namespace EATERY;

class EAT_Model_Setup extends EAT_Database
{
  private $database = null;

  public function __construct()
  { 
    
  }


  public function createTableUser()
  {
     $query = "CREATE TABLE IF NOT EXISTS user (
      id INT AUTO_INCREMENT PRIMARY KEY,
      email VARCHAR(255),
      encrypted_password VARCHAR(255),
      token VARCHAR(32),
      type CHAR(1)
      )";

     $result = $this->wdb()->query($query);
     
     if (!$result) {
      echo "unable to create table user";exit;
     }
  }

  public function createTableListing()
  {
     $query = "CREATE TABLE IF NOT EXISTS listing (
      id INT AUTO_INCREMENT PRIMARY KEY,
      list_name VARCHAR(45),
      distance FLOAT,
      user_id INT,
      FOREIGN KEY (user_id) REFERENCES user(id)
      )";

     $result = $this->wdb()->query($query);
     
     if (!$result) {
      echo "unable to create table listing";exit;
     }
  }



  
}