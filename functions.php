<?php
// generate a random integer for the main page
function gen_uid($l=9){
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $l);
}


function addText(){
    $request = Slim::getInstance()->request();
    $text = json_decode($request->getBody());
    $sql = "INSERT INTO textpad ()";
}


/* Generic CRUD functions */
class Database
{
     
    private static $cont  = null;
     
    public function __construct() {
        die('Init function is not allowed');
    }
     
    public static function connect()
    {
        
       global $dbName, $dbHost, $dbUsername, $dbUserPassword;
       // One connection through whole application
       if ( null == self::$cont )
       {     
        try
        {
          self::$cont =  new PDO( "mysql:host=".$dbHost.";"."dbname=".$dbName, $dbUsername, $dbUserPassword); 
        }
        catch(PDOException $e)
        {
          die($e->getMessage()); 
        }
       }
       return self::$cont;
    }
     
    public static function disconnect()
    {
        self::$cont = null;
    }
}