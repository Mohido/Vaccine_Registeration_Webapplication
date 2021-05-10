<?php

    session_start();
    // Loading (all) dependencies
    require_once(__DIR__ . "/utils/storage.inc.php");
    require_once(__DIR__ . "/utils/input.inc.php");
    require_once(__DIR__ . "/utils/auth.inc.php");

    $userStorage = new Storage(new JsonIO(__DIR__ . "/data/users.json"));
    $auth = new Auth($userStorage);

    $user = $auth->authenticated_user();


    if(!verify_get("mm", "dd" ,"yy", "hr","mn")){
    echo "Invalid URL data";
    }else{       
        $mm = $_GET["mm"];
        $dd = $_GET["dd"];
        $yy = $_GET["yy"];
        $hr = $_GET["hr"];
        $mn = $_GET["mn"];
        $lin = "dd=". $_GET["dd"] . "&mm=".$_GET["mm"] ."&yy=".$_GET["yy"] . "&hr=". $_GET["hr"] ."&mn=". $_GET["mn"];
    }

?>

<?php if(isset($user)):?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Booking Date</title>
        <link rel="stylesheet" href="styles/login.css">
    </head>
    <body>

    <nav class="main-nav"> 
        <h1 class="nav-header">Vaccine Appointments</h1>
    </nav>
    <main class="main-container">
            <section>
                <h1>Book-in</h1>
                <div class="section-content">
                    <h3 class="displayed-time">Book Time: <?= date("Y/M/d H:i", strtotime("$yy-$mm-$dd $hr:$mn"))?></h3>
                    
                    <form action="pure_backend/register_time.php?<?=$lin?>" novalidate method="post">
                        <label for="name">Name: </label>
                        <input class="info-show" type="text" id="name" value="<?=$user["fullname"]?>" disabled/>
                        <div class="line"></div>

                        <label for="ssn">SSN: </label>
                        <input class="info-show" type="text" id="ssn" value="<?=$user["SSN"]?>" disabled/>
                        <div class="line"></div>
                        
                        <label for="address">Address: </label>
                        <input class="info-show" type="text" id="address" value="<?=$user["address"]?>" disabled/>
                        <div class="line"></div>
                    
                        <br>
                        <label for="check">Acception of Privacy Policy rules: </label>
                        <input type="checkbox" id="check" name="check"/>
                        <?php
                            if(isset($_GET["checkoff"]) && $_GET["checkoff"] == 1){
                                echo "<div class=\"error\">Please accept the terms of agreement</div>";
                            }
                        ?>
                        <input type="submit" class="submit-btn" value="Submit"/>
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
<?php endif?>