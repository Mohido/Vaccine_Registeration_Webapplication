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
    $auth = new Auth($userStorage);

    if($auth->is_authenticated()){
        header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/index.php", true, 301);///carrying data to log-in page
        exit();
    }
    
    ///validating inputs...
    if(verify_post("email")){
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Invalid email format";
        }
    }else{
        $emailErr = "Email is required";
    }

    if(verify_post("pass")){
        $pass = test_input($_POST["pass"]);
    }else{
        $passErr = "Insert a Password";
    }

    ///Authinticate Logger...
    if(isset($pass) && isset($email) && !isset($emailErr)){
         // Initialize Auth class
         if(isset($_SESSION['user'])){
            unset($_SESSION['user']);
        }

        $user = $auth->authenticate($email, $pass);

        
        if(!is_null($user)){
            $auth->login($user);
            if(verify_get("mm", "dd" ,"yy", "hr","mn")){
                $lin = "dd=". $_GET["dd"] . "&mm=".$_GET["mm"] ."&yy=".$_GET["yy"] . "&hr=". $_GET["hr"] ."&mn=". $_GET["mn"];
                if(strlen($user["time"]) > 0){
                    header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/index.php", true, 301);
                    exit();
                }else{
                    header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/booking.php?$lin",true,301);
                    exit();
                }
            }else{
                header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/index.php",true,301);
                exit();
            }
            
            
        }else{
            $userNotFound = true;
        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <nav class="main-nav"> 
        <h1 class="nav-header">Vaccine Appointments</h1>
    </nav>

    <main class="main-container">
        <section>
            <h1>Log-in</h1>
            <div class="section-content">
                <form action="" novalidate method="post">
                    <h3 class="label">Email: </h3>
                    
                    <?php if(isset($email)):?>
                        <input class="costum_input" type="text" id="email" name="email" value="<?=$email?>" placeholder="e.g: email@something.com" required/>
                        <?php if(isset($emailErr)):?> <span class="error"><?=$emailErr?></span> <?php endif?>
                            
                    <?php else:?>
                        <input class="costum_input" type="text" id="email" name="email" placeholder="e.g: email@something.com" required/>
                    <?php endif?>


                    <div class="line"></div>
                    <h3 class="label">Passowrd: </h3>
                    <input class="costum_input" type="password" id="pass" name="pass" required/>
                    <div class="line"></div>
                    <input type="submit" class="submit-btn" value="Login">
                </form>
            </div>
        </section>
        <?php 
            if(isset($userNotFound)){
                echo "<div class=\"error\">User Not Found! or Invalid Password!</div>";
            }
        ?>
        <div class="line"></div>
        <div style="text-align:center;">
            <a href="index.php" class="link-btns"><button class="submit-btn" style="width: 200px;">Back to main page</button></a>
            <a href="register.php" class="link-btns"><button class="submit-btn" style="width: 200px;">I don't have an accout</button></a>
        </div>
       
    </main>


</body>
</html>