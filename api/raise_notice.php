<?php

    include_once 'remove_notice.php';
    include_once 'authenticate.php';

    // Takes raw data from the request
    $json = file_get_contents('php://input');

    // Converts it into a PHP object
    $data = json_decode($json);

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Notice raised successfully";

    foreach($data as $i => $x){
        if($i == "notice_title"){
            if(!$x){
                $obj -> status = "failure";
                $obj -> message = "Please give a Notice title.";
            }
        }
        if($i == "notice_subject"){
            if(!$x){
                $obj -> status = "failure";
                $obj -> message = "Please give a Notice subject.";
            }
        }
        if($i == "notice_content"){
            if(!$x){
                $obj -> status = "failure";
                $obj -> message = "Please give a Notice content.";
            }
        }
    }


    $data -> notice_title = mysqli_real_escape_string($mysqli, $data -> notice_title);
    $data -> notice_subject = mysqli_real_escape_string($mysqli, $data -> notice_subject);
    $data -> notice_content = mysqli_real_escape_string($mysqli, $data -> notice_content);



    if($obj -> status == "success"){
        include_once 'db_connect.php';
        $mysqli = OpenCon();
    
        $query = "INSERT INTO mgr_community_forum.notice (`notice_raiser_reg_no`, `notice_title`, `notice_subject`, `notice_value`) VALUES (".$_COOKIE['reg_no'].", '".$data -> notice_title."', '".$data -> notice_subject."', '".$data -> notice_content."')";
        $result = $mysqli -> query($query);

        if(!$result){
            $obj -> status = "failure";
            $obj -> message = "Something went wrong";
        }

        CloseCon($mysqli);
    }

    echo json_encode($obj);

?>