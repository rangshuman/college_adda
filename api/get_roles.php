<?php
    include_once 'remove_notice.php';
    
    include_once 'db_connect.php';
    $mysqli = OpenCon();

    $query = "SELECT * FROM mgr_community_forum.roles WHERE role_name != 'Superadmin' AND role_name != 'Admin'";
    $result = $mysqli -> query($query);

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Here are all the roles";

    $temp = array();

    if($result){
        $all = $result -> fetch_all();
        foreach($all as $x){
            $t = (object) array(
                'id' => $x[0],
                'name' => $x[1]
            );
            array_push($temp, $t);
        }
        $obj -> data = $temp;
    }
    else{
        $obj -> status = "failure";
        $obj -> message = "Something went wrong while getting the roles";
    }

    echo json_encode($obj);

    CloseCon($mysqli);

?>