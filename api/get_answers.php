<?php
    include_once 'remove_notice.php';

    include_once 'db_connect.php';

    $noExit = true;
    include_once 'authenticate.php';
    
    $mysqli = OpenCon();

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "Here are all the answers";

    if($_GET['reg_no']){
        $query = "SELECT mgr_community_forum.question.q_title, mgr_community_forum.question.id, mgr_community_forum.answer.id FROM mgr_community_forum.question INNER JOIN mgr_community_forum.answer ON mgr_community_forum.question.id = mgr_community_forum.answer.q_id WHERE mgr_community_forum.answer.reg_no_submitter = ".$_GET['reg_no'];
        $result = $mysqli -> query($query);

        $temp = array();

        if($result){
            $all = $result -> fetch_all();
            foreach($all as $x){
                $t = (object) array(
                    'q_title' => $x[0],
                    'q_id' => $x[1],
                    'ans_id' => $x[2]
                );
                array_push($temp, $t);
            }
            $obj -> data = $temp;
        }
        else{
            $obj -> status = "failure";
            $obj -> message = "Something went wrong while getting the answers";
        }

        echo json_encode($obj);
        exit;
    }


    $query = "SELECT mgr_community_forum.answer.ans_value, mgr_community_forum.answer.reg_no_submitter, mgr_community_forum.answer.id, mgr_community_forum.answer.upvote, mgr_community_forum.answer.downvote, mgr_community_forum.user.first_name, mgr_community_forum.user.last_name FROM mgr_community_forum.answer INNER JOIN mgr_community_forum.user ON mgr_community_forum.answer.reg_no_submitter=mgr_community_forum.user.reg_no WHERE mgr_community_forum.answer.q_id=".$_GET['q'];
    $result = $mysqli -> query($query);

    if($isLoggedIn){
        
        $query2 = "SELECT `vote_value`, `ans_id` FROM mgr_community_forum.votes WHERE q_id=".$_GET['q']." AND vote_type='answer' AND reg_no=".$_COOKIE['reg_no'];

        $result2 = $mysqli -> query($query2);
        $all_votes = $result2 -> fetch_all();

        $votes_arr = array();
        foreach($all_votes as $x){
            $total_vote = (object) array();
            $total_vote -> user_vote = (int)$x[0];
            $total_vote -> ans_id = (int)$x[1];
            array_push($votes_arr, $total_vote);
        }

    }

    $temp = array();

    if($result){
        $all = $result -> fetch_all();
        foreach($all as $x){
            $user_vote = null;
            foreach($votes_arr as $y){
                if($y -> ans_id == $x[2]){
                    $user_vote = $y -> user_vote;
                }
            }
            $t = (object) array(
                'ans_value' => stripslashes($x[0]),
                'reg_no_submitter' => $x[1],
                'id' => $x[2],
                'total_upvote' => (int)$x[3],
                'total_downvote' => (int)$x[4],
                'first_name' => $x[5],
                'last_name' => $x[6],
                'user_vote' => (int)$user_vote
            );
            array_push($temp, $t);
        }
        $obj -> data = $temp;
    }
    else{
        $obj -> status = "failure";
        $obj -> message = "Something went wrong while getting the answers";
    }

    echo json_encode($obj);

    CloseCon($mysqli);

?>