<?php
    session_start();
    // Loading (all) dependencies
    require_once(__DIR__ . "/../utils/storage.inc.php");
    require_once(__DIR__ . "/../utils/input.inc.php");
    require_once(__DIR__ . "/../utils/auth.inc.php");

    // Load (all) data sources
    $userStorage = new Storage(new JsonIO(__DIR__ . "/../data/users.json"));
    $auth = new Auth($userStorage);

    $user = $auth->authenticated_user();
    if(verify_get("mm", "dd" ,"yy", "hr","mn")){
        $lin = "dd=". $_GET["dd"] . "&mm=".$_GET["mm"] ."&yy=".$_GET["yy"] . "&hr=". $_GET["hr"] ."&mn=". $_GET["mn"];
        if($auth->is_authenticated() &&  $auth->authorize(["user"])){
            if(strlen($user["time"]) > 0){
                header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/index.php", true, 301);
                exit();
            }else{
                header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/booking.php?$lin", true, 301);
                exit();
            }
        }elseif($auth->is_authenticated() &&  $auth->authorize(["admin"])){
            header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/index.php?$lin", true, 301);
            exit();
        }else{
            header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/login.php?$lin", true, 301);///carrying data to log-in page
            exit();
        }
    }
?>