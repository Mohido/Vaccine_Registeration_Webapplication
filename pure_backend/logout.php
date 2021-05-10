<?php
        session_start();
        // Loading (all) dependencies
        require_once(__DIR__ . "/../utils/storage.inc.php");
        require_once(__DIR__ . "/../utils/input.inc.php");
        require_once(__DIR__ . "/../utils/auth.inc.php");

        // Load (all) data sources
        $userStorage = new Storage(new JsonIO(__DIR__ . "/../data/users.json"));
        $auth = new Auth($userStorage);

        
        if($auth->is_authenticated()){
            $auth->logout();
            header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/index.php", true, 301);
            exit();
        }
?>