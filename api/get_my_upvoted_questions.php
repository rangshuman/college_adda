<?php
    include_once 'remove_notice.php';
    
    include_once 'db_connect.php';

    $noExit = true;
    include_once 'authenticate.php';
    
    $mysqli = OpenCon();

    $query1 = "SELECT mgr_community_forum.votes.q_id, mgr_community_forum.question.q_title, mgr_community_forum.question.upvote, mgr_community_forum.question.downvote FROM mgr_community_forum.votes INNER JOIN mgr_community_forum.question ON mgr_community_forum.votes.q_id = mgr_community_forum.question.id WHERE reg_no=".$_GET['reg_no']." AND vote_value=".$_GET['upvote'];
    // var_dump($query1);
    $result1 = $mysqli -> query($query1);

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Here are all the upvoted questions";

    $all1 = $result1 -> fetch_all();

    $temp = array();
    if($all1){
        // $all = $result -> fetch_all();
        foreach($all1 as $x){
            $t = (object) array(
                'q_id' => ($x[0]),
                'q_title' => stripslashes($x[1]),
                'total_upvotes' => (int)$x[2],
                'total_downvotes' => (int)$x[3]
            );
            array_push($temp, $t);
        }
        $obj -> data = $temp;
    }
    else{
        $obj -> status = "failure";
        $obj -> message = "Something went wrong while getting the upvoted questions";
    }

    echo json_encode($obj);

    CloseCon($mysqli);

?>