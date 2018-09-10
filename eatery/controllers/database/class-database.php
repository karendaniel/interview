<?php
namespace EATERY;

class EAT_Database extends EAT_Setup
{
  public $tblPrefix = null;

  public function __construct()
  { 

  }

  public function wdb()
  {
      global $wpdb;

      $this->tblPrefix = $wpdb->prefix;

      return $wpdb;
  }

  public static function wdbStatic()
  {
      global $wpdb;

      return $wpdb;
  }

  public function connect()
  {
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "";

    try {
        $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $conn;
        }
    catch(PDOException $e)
        {
        echo "Error: " . $e->getMessage();
        }
  }
  
  
}