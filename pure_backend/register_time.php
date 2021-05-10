<?php


    
    session_start();
    // Loading (all) dependencies
    require_once(__DIR__ . "/../utils/storage.inc.php");
    require_once(__DIR__ . "/../utils/input.inc.php");
    require_once(__DIR__ . "/../utils/auth.inc.php");

    if(!isset($_POST["check"])){
        $lin = "dd=". $_GET["dd"] . "&mm=".$_GET["mm"] ."&yy=".$_GET["yy"] . "&hr=". $_GET["hr"] ."&mn=". $_GET["mn"] . "&checkoff=1";
        header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/booking.php?$lin", true, 301);
        exit();
    }else{
        $userStorage = new Storage(new JsonIO(__DIR__ . "/../data/users.json"));
        $timeStorage = new Storage(new JsonIO(__DIR__ . "/../data/time.json"));
        $auth = new Auth($userStorage);
    
        $user = $auth->authenticated_user();
        if(verify_get("mm", "dd" ,"yy", "hr","mn")){
            ///updating user time.
            $mm = $_GET["mm"];
            $dd = $_GET["dd"];
            $yy = $_GET["yy"];
            $hr = $_GET["hr"];
            $mn = $_GET["mn"];
            $user["time"] = "$yy-$mm-$dd $hr:$mn";
            $userStorage->update($user["id"], $user);

            ///updating time.json
            $times = $timeStorage->findAll(["time" => $user["time"]]);
            
            foreach ($times as $time){
                $time["users"][] = $user["id"];
                $timeStorage->update($time["id"], $time);
                break;
            }
            $auth->login($user );
            echo "<h1>Time Registered. Thank you for registering and have a nice day. <a href=\"http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res\"> Main Page</a></h1>";
        }
    }

    
    
?>