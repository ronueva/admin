<?php


define('DB_HOST', 'localhost');
define('DB_USERNAME', 'id476319_ecoordinator2017');
define('DB_PASSWORD', '01610218');
define('DB_NAME', 'id476319_ecoordinator_db');

//db connection class using singleton pattern
class dbConn
{

    //variable to hold connection object.
    protected static $db;

    //private construct - class cannot be instatiated externally.
    private function __construct()
    {

        try {
            $dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';port=3306';
            $username = DB_USERNAME;
            $password = DB_PASSWORD;
            // assign PDO object to db variable
            self::$db = new PDO($dsn, $username, $password);
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //Output error - would normally log this to error file rather than output to user.
            echo "Connection Error: " . $e->getMessage();
        }

    }

    // get connection function. Static method - accessible without instantiation
    public static function getConnection()
    {

        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$db) {
            //new connection object.
            new dbConn();
        }

        //return connection.
        return self::$db;
    }

    public function __sleep()
    {
        return array('company_id', 'company_name', 'company_username', 'company_password');
    }

    public function __wakeup()
    {
        $this->getConnection();
    }

}//end class

?>