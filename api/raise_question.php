<?php

    include_once 'remove_notice.php';
    include_once 'authenticate.php';

    // Takes raw data from the request
    $json = file_get_contents('php://input');

    // Converts it into a PHP object
    $data = json_decode($json);

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Question raised successfully";

    foreach($data as $i => $x){
        if($i == "question_title"){
            if(!$x){
                $obj -> status = "failure";
                $obj -> message = "Please give a question title.";
            }
        }
    }


    $data -> question_title = mysqli_real_escape_string($mysqli, $data -> question_title);
    $data -> question_description = mysqli_real_escape_string($mysqli, $data -> question_description);



    if($obj -> status == "success"){
        include_once 'db_connect.php';
        $mysqli = OpenCon();
    
        $query = "INSERT INTO mgr_community_forum.question (`reg_no_asker`, `q_title`, `q_value`) VALUES (".$_COOKIE['reg_no'].", '".$data -> question_title."', '".$data -> question_description."')";

        
        $result = $mysqli -> query($query);

        if(!$result){
            $obj -> status = "failure";
            $obj -> message = "Something went wrong";
        }

        CloseCon($mysqli);
    }

    echo json_encode($obj);

?>