<?php

    include_once 'remove_notice.php';

    include_once 'db_connect.php';
    $mysqli = OpenCon();

    $query = "SELECT * FROM mgr_community_forum.user WHERE reg_no= ".$_COOKIE['reg_no']." AND auth_token= '".$_COOKIE['auth_token']."'";
    
    $result = $mysqli -> query($query);
    
    $isLoggedIn = true;

    if(!$result || !$result -> fetch_row()){
        $isLoggedIn = false;
        $obj = (object) array();
        $obj -> status = "failure";
        CloseCon($mysqli);
        $obj -> message = "User is not logged in";
        if(!$noExit){
            echo json_encode($obj);
            exit;
        }
    }

?>