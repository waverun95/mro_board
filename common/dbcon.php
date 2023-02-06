<?php 
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type:text/html;charset=utf-8");

$conn = mysqli_connect("localhost","root","qwe789","homework");

class dbClass {
    var $conn = null;
    var $query = null;
    var $result	= null;
    var $hostname = "localhost";
    var $username = "root";
    var $password = "qwe789";
    var $database = "homework";

    function __construct()
    {
        $this -> connection();
    }

    function connection()
    {
        $this -> conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->database) or exit("db error");
    }
    function executeQuery($query)
    {
        $this -> query = $query;
        $this -> result = mysqli_query($this -> conn, $this -> query);

        return $this -> result;
    }
    function executeResult($query)
    {
        return mysqli_fetch_array($this->executeQuery($query));
    }
}
$db = new dbClass();
?>