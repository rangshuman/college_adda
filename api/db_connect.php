<?php
    include_once 'remove_notice.php';
    function OpenCon(){
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $db = "mgr_community_forum";

        $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n". $conn -> error);
        return $conn;
    }
    
    function CloseCon($conn){
        $conn -> close();
    }
?>