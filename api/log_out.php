<?php
    include_once 'remove_notice.php';

    include_once 'db_connect.php';

    $mysqli = OpenCon();

    $query = "UPDATE mgr_community_forum.user SET `auth_token` = NULL, `login_time` = NULL WHERE reg_no= ".$_COOKIE['reg_no'];

    $result = $mysqli -> query($query);


    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Logged out successfully";

    if(!$result){
        $obj -> status = "failure";
        $obj -> message = "Something went wrong";
    }
    else{
        setcookie("reg_no", "", time() - 3600);
        setcookie("auth_token", "", time() - 3600);
    }

    echo json_encode($obj);

?>