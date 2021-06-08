<?php
    include_once 'authenticate.php';

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "User is logged in";
    echo json_encode($obj);
?>