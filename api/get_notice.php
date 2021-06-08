<?php
    // include_once 'remove_notice.php';
    
    include_once 'db_connect.php';

    $noExit = true;
    include_once 'authenticate.php';
    
    $mysqli = OpenCon();

    // if($_GET['reg_no']){
    //     $query = "SELECT `q_title`, `q_value`, `id`, `upvote`, `downvote` FROM mgr_community_forum.question WHERE reg_no_asker=".$_GET['reg_no'];
    // }
    // else if($_GET['q']){
    //     $query = "SELECT `q_title`, `q_value`, `id` FROM mgr_community_forum.question WHERE id=".$_GET['q'];
    // }
    // else{
    //     $query = "SELECT `q_title`, `q_value`, `id`, `upvote`, `downvote` FROM mgr_community_forum.question";
    // }  
    
    $query = "SELECT `notice_title`, `notice_subject`, `notice_value` FROM mgr_community_forum.notice";
    $result = $mysqli -> query($query);


    // if($isLoggedIn && $_GET['q']){

    //     $query2 = "SELECT `vote_value`, `reg_no` FROM mgr_community_forum.votes WHERE q_id=".$_GET['q'];
    //     $result2 = $mysqli -> query($query2);

    //     $total_vote = (object) array();
    //     // $total_vote -> upvote = 0;
    //     // $total_vote -> downvote = 0;
    //     $all_votes = $result2 -> fetch_all();

    //     foreach($all_votes as $x){
    //         // if((int)$x[0] == 1){
    //         //     $total_vote -> upvote++;
    //         // }
    //         // if((int)$x[0] == -1){
    //         //     $total_vote -> downvote++;
    //         // }
    //         if($x[1] == $_COOKIE['reg_no']){
    //             $total_vote -> user_vote = (int)$x[0];
    //         }
    //     }

    // }

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Here are all the notices";

    $temp = array();
    $all = $result -> fetch_all();
    
    if($all){
        foreach($all as $x){
            $t = (object) array(
                'notice_title' => stripslashes($x[0]),
                'notice_subject' => stripslashes($x[1]),
                'notice_value' => stripslashes($x[2])
            );
            array_push($temp, $t);
        }
        $obj -> data = $temp;
    }
    else{
        $obj -> status = "failure";
        $obj -> message = "Something went wrong while getting the notices";
    }

    echo json_encode($obj);

    CloseCon($mysqli);

?>