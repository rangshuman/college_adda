<?php
  // include_once 'remove_notice.php';

  // Takes raw data from the request
  $json = file_get_contents('php://input');

  // Converts it into a PHP object
  $data = json_decode($json);
  
  $obj = (object) array();
  $obj -> status = "success";
  $obj -> message = "Your data has been collected successfully. Approval Pending.";

  foreach($data as $i => $x){
    if($i == "first_name"){
      if(!$x){
        $obj -> status = "failure";
        $obj -> message = "Please Provide First Name.";
      }
    } else if($i == "last_name"){
      if(!$x){
        $obj -> status = "failure";
        $obj -> message = "Please Provide Last Name.";
      }
    } else if($i == "reg_no"){
      if(!$x){
        $obj -> status = "failure";
        $obj -> message = "Please Provide Registration Number.";
      }
      else{
        if(!is_numeric($x)){
          $obj -> status = "failure";
          $obj -> message = "Registration Number Should Be Numeric";
        }
      }
    } else if($i == "admit_year"){
      if(!$x){
        $obj -> status = "failure";
        $obj -> message = "Please Provide Admission Year.";
      }
    } else if($i == "email"){
      if(!$x){
        $obj -> status = "failure";
        $obj -> message = "Please Provide Email ID.";
      }
      else{
        if(!filter_var($x, FILTER_VALIDATE_EMAIL)){
          $obj -> status = "failure";
          $obj -> message = "Please Provide Valid Email ID.";
        }
      }
    }
    else if($i == "mobile"){
      if(!$x){
        $obj -> status = "failure";
        $obj -> message = "Please Provide Mobile Number.";
      }
    }
    else if($i == "password"){
      if(!$x){
        $obj -> status = "failure";
        $obj -> message = "Please Provide A Password.";
      }
      else if(strlen($x) < 8){
        $obj -> status = "failure";
        $obj -> message = "Password should be of at least 8 Characters.";
      }
    }
    else if($i == "role_id"){
      if(!$x){
        $obj -> status = "failure";
        $obj -> message = "Please Select Role.";
      }
    }
  }

  if($obj -> status == "success"){
    include_once 'db_connect.php';
    $mysqli = OpenCon();

    $query2 = "SELECT `reg_no` FROM mgr_community_forum.user WHERE reg_no=".$data -> reg_no."";
    $result2 = $mysqli -> query($query2);

    $result2f = $result2 -> fetch_all();

    if($result2f){
      $obj -> status = "failure";
      $obj -> message = "User already exists with this Registration number.";
    }
    else{
      $query = "INSERT INTO mgr_community_forum.user (`first_name`, `last_name`, `reg_no`, `admit_year`, `email`, `mobile`, `role_id`, `password`) VALUES ('".$data -> first_name."', '".$data -> last_name."', ".$data -> reg_no.", ".$data -> admit_year.", '".$data -> email."', ".$data -> mobile.", ".$data -> role_id.", '".md5($data -> password)."')";
    
      $result = $mysqli -> query($query);

      if(!$result){
        $obj -> status = "failure";
        $obj -> message = "Something went wrong";
      }
    }

    CloseCon($mysqli);
  }

  echo json_encode($obj);

?>