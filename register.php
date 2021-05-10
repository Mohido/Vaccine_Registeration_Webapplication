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

        ///verifying inputs
        if(verify_post("fullname")){
            $name = test_input($_POST["fullname"]);
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
              $nameErr = "Only letters and white space allowed";
            }
        }else{
            $nameErr = "fullname is required";
        }

        if(verify_post("SSN")){
            $ssn = test_input($_POST["SSN"]);
            if (!preg_match("/[0-9]{9}/",$ssn)) {
              $ssnErr = "exactly 9 numbers are allowed!";
            }
        }else{
            $ssnErr = "social security number is required";
        }

        if(verify_post("address")){
            $address = test_input($_POST["address"]);
        }else{
            $addressErr = "address is required!";
        }

        if(verify_post("email")){
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $emailErr = "Invalid email format";
            }
        }else{
            $emailErr = "Email is required";
        }

        if(verify_post("pass", "pass2")){
            $pass = test_input($_POST["pass"]);
            $pass2 = test_input($_POST["pass2"]);
            if($pass !== $pass2){
                $passErr = "Both Passwords should Match";
            }
        }

        /// Check if the account exists or register
        if(!isset($passErr) && !isset($emailErr) && !isset($nameErr) && !isset($addressErr) && !isset($ssnErr)){
            $data = [
                "fullname"  => $name,
                "address" => $address,
                "SSN" => $ssn,
                "email" => $email,
                "password"  => $pass,     
            ];
            if( !is_null($auth -> register($data)) ){
                header("Location: http://webprogramming.inf.elte.hu/students/zyqsyj/vaxin_res/login.php");
                exit();
            }else{
                $registerErr = "Email already exists. Contact us as soon as possible. We don't have a forgot password option sorry :)";
            }
        }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles/login.css"/>
</head>
<body>
    <nav class="main-nav"> 
        <h1 class="nav-header">Vaccine Appointments</h1>
    </nav>

    <main class="main-container">  
        <section>
            <h1>Register</h1>
            <div class="section-content">
                <form action="" novalidate method="post">
                    
                    <h3 class="label">Fullname: </h3>
                    <?php if(isset($name)):?>
                        <input class="costum_input" type="text" name="fullname" placeholder="e.g: Michael Schumacher" required value="<?=$name?>"/>
                        <?php if(isset($nameErr)):?> <span class="error"><?=$nameErr?></span> <?php endif?>
                    <?php else:?>
                        <input class="costum_input" type="text" name="fullname" placeholder="e.g: Michael Schumacher" required/>
                    <?php endif?>

                    <div class="line"></div>

                    <h3 class="label">SSN (Social Security No.): </h3>
                    <?php if(isset($ssn)):?>
                        <input class="costum_input" type="text" name="SSN" placeholder="e.g: 123456789" required  value="<?=$ssn?>"/>
                        <?php if(isset($ssnErr)):?> <span class="error"><?=$ssnErr?></span> <?php endif?>
                    <?php else:?>
                        <input class="costum_input" type="text" name="SSN" placeholder="e.g: 123456789" required/>
                    <?php endif?>
                    <div class="line"></div>

                    <h3 class="label">Address: </h3>
                    <?php if(isset($address)):?>
                        <input class="costum_input" style="width:270px;" type="text" name="address" placeholder="e.g: Hungary, Budapest, infopark, 32, 1061" required value="<?=$address?>"/>
                        <?php if(isset($addressErr)):?> <span class="error"><?=$addressErr?></span> <?php endif?>
                    <?php else:?>
                        <input class="costum_input" style="width:270px;" type="text" name="address" placeholder="e.g: Hungary, Budapest, infopark, 32, 1061" required/>
                    <?php endif?>


                    <div class="line"></div> 

                    <h3 class="label">Email: </h3>
                    <?php if(isset($email)):?>
                        <input class="costum_input" type="text" name="email" value="<?=$email?>" placeholder="e.g: email@something.com" required/>
                        <?php if(isset($emailErr)):?> <span class="error"><?=$emailErr?></span> <?php endif?>
                    <?php else:?>
                        <input class="costum_input" type="text" name="email" placeholder="e.g: email@something.com" required/>
                    <?php endif?>

                    <div class="line"></div>

                    <h3 class="label">Password: </h3>
                    <input class="costum_input" type="password" name="pass" required/>
                    <h3 class="label">Password Confirmation: </h3>
                    <input class="costum_input" type="password" name="pass2" required/>
                    
                    <?php if(isset($passErr)):?> <span class="error"><?=$passErr?></span> <?php endif?>

                    <div class="line"></div>

                    <input type="submit" class="submit-btn" value="Register">
                    

                </form>

            </div>
        </section>  
        <div class="line"></div>
        <div style="text-align:center;">
            <a href="index.php" class="link-btns"><button class="submit-btn" style="width: 200px;">Back to main page</button></a>
            <a href="login.php" class="link-btns"><button class="submit-btn" style="width: 200px;">I already have an account</button></a>
        </div>
        <br><br>
    </main>
</body>
</html>