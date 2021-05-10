<?php
    session_start();
    //Functions area
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

    // Loading (all) dependencies
    require_once(__DIR__ . "/utils/storage.inc.php");
    require_once(__DIR__ . "/utils/input.inc.php");
    require_once(__DIR__ . "/utils/auth.inc.php");

    $userStorage = new Storage(new JsonIO(__DIR__ . "/data/users.json"));
    $timeStorage = new Storage(new JsonIO(__DIR__ . "/data/time.json"));
    $auth = new Auth($userStorage);

    //var_dump($_POST);
    if(verify_post("date") && strlen($_POST["date"]) > 0){
        $date = $_POST["date"];
    }else{
        $dateErr = "Required Feild!";
    }

    if(verify_post( "hour")  && strlen($_POST["hour"]) > 0){
        $hour = $_POST["hour"];
    }else{
        $hourErr = "Required Feild!";
    }

    if(verify_post("slot") && strlen($_POST["slot"]) > 0){
        $slot = $_POST["slot"];
    }else{
        $slotErr = "Required Feild!";
    }

    if(isset($slot) && isset($date) && isset($hour) ){
        if(strtotime($date) < strtotime(date("Y-m-d"))){
            $dateErr = "Can't register a time slot in the past!";
        }elseif( (int)$slot <= 0 ){
            $slotErr = "Positive Count required";
        }else{
            $time = ["slots" => $slot , "time" => "$date $hour" , "users" => []];
            var_dump($time);
            $timeStorage -> add($time);
            header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/index.php", true, 301);
            exit();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time_register</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <nav class="main-nav"> 
        <h1 class="nav-header">Vaccine Appointments</h1>
    </nav>

    <main class="main-container">
        <section>
            <h1>Time-Register</h1>
            <div class="section-content">
                <form action="" novalidate method="post">
                    <h3 class="label">Time: </h3>
                    
                    <?php if(isset($date)):?>
                        <input class="costum_input" type="date" id="date" name="date" value="<?=$date?>" required/>
                        <?php if(isset($dateErr)):?> <span class="error"><?=$dateErr?></span> <?php endif?>
                    <?php else:?>
                        <input class="costum_input" type="date" id="date" name="date" required/>
                    <?php endif?>

                    <h3 class="label">Time Validate: </h3>
                    <?php if(isset($hour)):?>
                        <input class="costum_input" type="time" id="hour" name="hour" value="<?=$hour?>" required/>
                        <?php if(isset($hourErr)):?> <span class="error"><?=$hourErr?></span> <?php endif?>
                    <?php else:?>
                        <input class="costum_input" type="time" id="hour" name="hour" required/>
                    <?php endif?>

                    <div class="line"></div>
                    <h3 class="label">slots: </h3>
                    <input class="costum_input" type="number" id="slot" name="slot" required/>
                    <div class="line"></div>
                    <input type="submit" class="submit-btn" value="Add">
                </form>
            </div>
        </section>

        <div class="line"></div>
        <div style="text-align:center;">
            <a href="index.php" class="link-btns"><button class="submit-btn" style="width: 200px;">Back to main page</button></a>
        </div>
       
    </main>
</body>
</html>