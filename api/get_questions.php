<?php
    include_once 'remove_notice.php';
    
    include_once 'db_connect.php';

    $noExit = true;
    include_once 'authenticate.php';
    
    $mysqli = OpenCon();

    if($_GET['reg_no']){
        $query = "SELECT `q_title`, `q_value`, `id`, `upvote`, `downvote` FROM mgr_community_forum.question WHERE reg_no_asker=".$_GET['reg_no'];
    }
    else if($_GET['q']){
        $query = "SELECT `q_title`, `q_value`, `id`, `upvote`, `downvote` FROM mgr_community_forum.question WHERE id=".$_GET['q'];
    }
    else{
        $query = "SELECT `q_title`, `q_value`, `id`, `upvote`, `downvote` FROM mgr_community_forum.question";
    }    
    $result = $mysqli -> query($query);


    if($isLoggedIn && $_GET['q']){

        $query2 = "SELECT `vote_value`, `reg_no` FROM mgr_community_forum.votes WHERE q_id=".$_GET['q'];
        $result2 = $mysqli -> query($query2);

        $total_vote = (object) array();
        // $total_vote -> upvote = 0;
        // $total_vote -> downvote = 0;
        $all_votes = $result2 -> fetch_all();

        foreach($all_votes as $x){
            // if((int)$x[0] == 1){
            //     $total_vote -> upvote++;
            // }
            // if((int)$x[0] == -1){
            //     $total_vote -> downvote++;
            // }
            if($x[1] == $_COOKIE['reg_no']){
                $total_vote -> user_vote = (int)$x[0];
            }
        }

    }

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Here are all the questions";

    $temp = array();
    if($result){
        $all = $result -> fetch_all();
        foreach($all as $x){
            $t = (object) array(
                'q_title' => stripslashes($x[0]),
                'q_value' => stripslashes($x[1]),
                'id' => $x[2],
                // 'upvote' => $total_vote -> upvote,
                // 'downvote' => $total_vote -> downvote,
                'user_vote' => $total_vote -> user_vote,
                'total_upvotes' => (int)$x[3],
                'total_downvotes' => (int)$x[4],
            );
            array_push($temp, $t);
        }
        $obj -> data = $temp;
    }
    else{
        $obj -> status = "failure";
        $obj -> message = "Something went wrong while getting the questions";
    }

    echo json_encode($obj);

    CloseCon($mysqli);

?>