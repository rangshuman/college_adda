<?php
    include_once 'remove_notice.php';
    
    include 'db_connect.php';
    $mysqli = OpenCon();

    $query = "SELECT mgr_community_forum.user.first_name, mgr_community_forum.user.last_name, mgr_community_forum.user.reg_no, mgr_community_forum.user.email, mgr_community_forum.user.role_id, mgr_community_forum.roles.role_name FROM mgr_community_forum.user INNER JOIN mgr_community_forum.roles ON mgr_community_forum.user.role_id = mgr_community_forum.roles.id WHERE reg_no = ".$_COOKIE['reg_no']." AND auth_token = '".$_COOKIE['auth_token']."'";
    $result = $mysqli -> query($query);


    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Here is the user's full name";

    if($result){
        $all = $result -> fetch_row();
        $data = (object) array();
        $data -> first_name = $all[0];
        $data -> last_name = $all[1];
        $data -> reg_no = $all[2];
        $data -> email = $all[3];
        $data -> role_id = $all[4];
        $data -> role_name = $all[5];

        $obj -> data = $data;
    }
    else{
        $obj -> status = "failure";
        $obj -> message = "Something went wrong while getting the user's name";
    }

    echo json_encode($obj);

    CloseCon($mysqli);

?>