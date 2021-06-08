<?php

    include_once 'remove_notice.php';
    include_once 'authenticate.php';

    // Takes raw data from the request
    $json = file_get_contents('php://input');

    // Converts it into a PHP object
    $data = json_decode($json);

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Answer submitted successfully";

    foreach($data as $i => $x){
        if($i == "answer"){
            if(!$x){
                $obj -> status = "failure";
                $obj -> message = "Please give an answer.";
            }
        }
    }

    $data -> ans_value = mysqli_real_escape_string($mysqli, $data -> ans_value);

    if($obj -> status == "success"){
        include_once 'db_connect.php';
        $mysqli = OpenCon();
    
        $query = "INSERT INTO mgr_community_forum.answer (`reg_no_submitter`, `q_id`, `ans_value`) VALUES (".$_COOKIE['reg_no'].", ".$data -> q_id.", '".$data -> answer."')";
        
        $result = $mysqli -> query($query);

        if(!$result){
            $obj -> status = "failure";
            $obj -> message = "Something went wrong";
        }

        CloseCon($mysqli);
    }

    echo json_encode($obj);

?>