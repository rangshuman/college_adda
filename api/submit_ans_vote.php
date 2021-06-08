<?php

    include_once 'remove_notice.php';
    include_once 'authenticate.php';

    $json = file_get_contents('php://input');

    $data = json_decode($json);

    $obj = (object) array();
    $obj -> status = "success";
    $obj -> message = "";

    foreach($data as $i => $x){
        if($i == "vote_value"){
            if($x == 1){
                $obj -> message = "Answer upvoted successfully.";
            }
            elseif($x == -1){
                $obj -> message = "Answer downvoted successfully.";
            }
        }
    }

    if($obj -> status == "success"){

        include_once 'db_connect.php';
        $mysqli = OpenCon();

        $query3 = "SELECT * FROM mgr_community_forum.votes WHERE ans_id=".$data -> ans_id." AND reg_no = ".$_COOKIE['reg_no'];
        $result3 = $mysqli -> query($query3);
        $result3_all = $result3 -> fetch_all();

        if($result3_all != null){
            
            $query2 = "SELECT * FROM mgr_community_forum.votes WHERE ans_id=".$data -> ans_id." AND vote_value = ".$data -> vote_value." AND reg_no = ".$_COOKIE['reg_no'];
            $result2 = $mysqli -> query($query2);
            $result2_all = $result2 -> fetch_all();

            if($result2_all){
                $query = "UPDATE mgr_community_forum.votes SET `vote_value` = 0 WHERE ans_id=".$data -> ans_id." AND reg_no = ".$_COOKIE['reg_no'];
            }
            else{
                $query = "UPDATE mgr_community_forum.votes SET `vote_value` = ".$data -> vote_value." WHERE ans_id=".$data -> ans_id." AND reg_no = ".$_COOKIE['reg_no'];
            }
            $result = $mysqli -> query($query);

        }
        else{

            $query = "INSERT INTO mgr_community_forum.votes (`reg_no`, `vote_type`, `q_id`, `ans_id`, `vote_value`) VALUES (".$_COOKIE['reg_no'].", 'answer', ".$data -> q_id.", ".$data -> ans_id.", ".$data -> vote_value.")";
            $result = $mysqli -> query($query);

        }

        $query4 = "SELECT `vote_value` FROM mgr_community_forum.votes WHERE ans_id=".$data -> ans_id;
        $result4 = $mysqli -> query($query4);

        
        $final_up_vote_count = 0;
        $final_down_vote_count = 0;
        foreach($result4 -> fetch_all() as $x){
            if((int)$x[0] == 1){
                $final_up_vote_count++;
            }
            elseif ((int)$x[0] == -1) {
                $final_down_vote_count++;
            }
        }

        $query5 = "UPDATE mgr_community_forum.answer SET upvote =".$final_up_vote_count.", downvote =".$final_down_vote_count." WHERE id=".$data -> ans_id;
        
        $result5 = $mysqli -> query($query5);

        if(!$result || !$result5){
            $obj -> status = "failure";
            $obj -> message = "Something went wrong";
        }
        

        CloseCon($mysqli);

    }

    echo json_encode($obj);

?>

