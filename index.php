<?php
    require_once(__DIR__."/utils/_init.php"); //Getting Database Data + Authinticating the use
    $user = $auth->authenticated_user();
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccine Appointment</title>
    <link rel="stylesheet" href="styles/index.css">
</head>
<body>
    <nav class="main-nav"> 
        <h1 class="nav-header">Vaccine Appointments</h1>
        <div class="nav-options">
            <?php if ($auth->is_authenticated() ): ?>
                    <a href="pure_backend/logout.php">Logout</a>
            <?php else: ?>
                    <a href="login.php">Login</a><span></span><a href="register.php">Register</a>
            <?php endif ?>
        </div>
    </nav>

    <main class="main-container">

        <?php if ($auth->is_authenticated() && $auth->authorize(["user"])): ?>
            <section>
                <h1>Reserved Appointment</h1>
                <div class="reserved-details section-content">
                    <?php if( strlen($user["time"]) > 0): ?>
                        <div>Reserved Date and Time: <?= $user["time"] ?> </div> <a class="delete-link" href="pure_backend/delete_appointment.php"><button class="delete-btn">Delete</button></a>
                    <?php else:?>
                        <h2>You have no appointments, click on one of the given slots in the calender down below to book an appointment. </h2>
                    <?php endif?>
                    
                </div>
            </section>
        <?php elseif($auth->is_authenticated() && $auth->authorize(["admin"])):?>
            <section>
                <h1>Admins Options</h1>
                <div class="reserved-details section-content">
                        <div>To Add a time slot: <a class="add-time-link" href="time_register.php"><button class="add-time-btn">Add Time</button></a></div>
                        <div>To Check who are the users on a specific time, click on the time and the users details will show below. </div>
                </div>
            </section>
        <?php endif ?>

        <?php if($auth->is_authenticated() && $auth->authorize(["admin"]) && verify_get("mm", "dd" ,"yy", "hr","mn") ): ?>
            <section>
                <h1>Times and Registerd People:</h1>
                <div class="section-content">
                    <?php 
                        $lin = $_GET["yy"]."-".$_GET["mm"]."-".$_GET["dd"]." ". $_GET["hr"]. ":". $_GET["mn"];
                        $timeData = new Storage(new JsonIO(__DIR__ . "/data/time.json"));
                        $timeObj = $timeData->findOne(["time" => $lin]);?>
                        
                    <?php if(!is_null($timeObj) && !is_null($timeObj["users"]) && count($timeObj["users"] > 0)):?>
                        <h3 class="time-header"><?=$timeObj["time"]?></h3>
                        <ul class="users-list">
                            <?php foreach( $timeObj["users"] as $userId):?>
                                <?php $u = $userStorage->findById($userId)?>
                                <li><?=$u["fullname"]?> , <?=$u["SSN"]?>, <?=$u["email"]?></li> 
                            <?php endforeach ?>
                         </ul>
                    <?php endif ?>
                </div>
            </section>
        <?php endif ?>

        <section>
            <h1>Appointments</h1>
            <div class="section-content">
                <h2 class="month-word">Month: <span id="month_header"></span></h2>
                <div class="search-input">
                    <label for="search-month">Choose a month: </label>
                    <input type="month" id="search-month" name="search-month" value="2021-01"/>
                    <button id="search-btn" >View</button>
                </div>
                
                <table id="given-dates"></table> <!-- Filled in the index.js by getting information from the database -->

                <div class="prev-next-area" >
                    <button id="prev-month" class="mnth-navs" >Previous</button>
                    <div id="mnth-btns"></div>
                    <button id="next-month" class="mnth-navs">Next</button>
                </div>
                
            </div>
        </section>


        

    </main>
    
    <script src="js/index.js"></script>
</body>
</html>