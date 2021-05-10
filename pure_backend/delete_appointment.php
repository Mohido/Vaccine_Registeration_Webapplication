<?php

    session_start();
    // Loading (all) dependencies
    require_once(__DIR__ . "/../utils/storage.inc.php");
    require_once(__DIR__ . "/../utils/input.inc.php");
    require_once(__DIR__ . "/../utils/auth.inc.php");

    // Load (all) data sources
    $userStorage = new Storage(new JsonIO(__DIR__ . "/../data/users.json"));
    $timeStorage = new Storage(new JsonIO(__DIR__ . "/../data/time.json"));
    $auth = new Auth($userStorage);

    if($auth->is_authenticated()){
        $user = $auth->authenticated_user();

        ///updating time.json database...
        $times = $timeStorage->findAll(["time" => $user["time"]]);
        foreach ($times as $time){
            if(in_array( $user["id"], $time["users"]) ){
                $time["users"] = array_diff($time["users"],$user["id"]) ?? [];
                $timeStorage->update($time["id"], $time);
                break;
            }
        }

        ///updating user.json
        $user["time"] = "";
        $userStorage->update($user["id"], $user);

        ///updating the authuntication with the new information
        $auth->login($user);
        header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/index.php",true,301);
        exit();
    }
?>