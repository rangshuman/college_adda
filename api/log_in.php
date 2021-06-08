<?php
    
    include_once 'remove_notice.php';

    // Takes raw data from the request
    $json = file_get_contents('php://input');

    // Converts it into a PHP object
    $data = json_decode($json);

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Logged in successfully";

    foreach($data as $i => $x){
        if($i == "reg_no"){
            if(!$x){
                $obj -> status = "failure";
                $obj -> message = "Please Provide Registration number.";
            }
        } else if($i == "password"){
            if(!$x){
                $obj -> status = "failure";
                $obj -> message = "Please enter password.";
            }
        }
    }
     
    if($obj -> status == "success"){
        include_once 'db_connect.php';
        $mysqli = OpenCon();

        $reg_no = $data -> reg_no;
        $password = md5($data -> password);
    
        $query = "SELECT * FROM mgr_community_forum.user WHERE reg_no= ".$reg_no." AND password= '".$password."'";
        
        $result = $mysqli -> query($query);
    
        if(!$result -> fetch_all()){
            $obj -> status = "failure";
            $obj -> message = "Incorrect Registration Number or Password.";
        }
        else{
            $cookie_name = "auth_token";
            $auth_token = md5(strval(rand()) . $reg_no);
            setcookie($cookie_name, $auth_token, time() + (86400 * 30), "/"); // 86400 = 1 day

            $cookie_name = "reg_no";
            setcookie($cookie_name, $reg_no, time() + (86400 * 30), "/"); // 86400 = 1 day

            $query = "UPDATE mgr_community_forum.user SET `auth_token` = '".$auth_token."', `login_time` = ".strtotime("now")." WHERE reg_no= ".$reg_no."";
    
            $result = $mysqli -> query($query);
            // strtotime("now")
        }

        CloseCon($mysqli);
    }

    echo json_encode($obj);

?>