<?php

session_start();

// Loading (all) dependencies
require_once(__DIR__ . "/utils/storage.inc.php");
require_once(__DIR__ . "/utils/input.inc.php");
require_once(__DIR__ . "/utils/auth.inc.php");

// Load (all) data sources
$timeData = new Storage(new JsonIO(__DIR__ . "/data/time.json"));
$userStorage = new Storage(new JsonIO(__DIR__ . "/data/users.json"));
$auth = new Auth($userStorage);
$user = $auth->authenticated_user();

$data = $timeData->findAll();
$calRows = 6;
$calCol = 7;
//$days['day' => '', 'times'=>[]];
                                            //     0                       1: IDs for the days then the table will be instructed on these later
$daysTime = array_fill(0, (6 * 7) , ['day' => '', 'times' => [] ] );    //[[time, time, time], [time, time, time]...];

if ( verify_get("mn", "yr") ){
    $mn = $_GET["mn"];
    $yr = $_GET["yr"];

    switch( date("D",strtotime("$yr-$mn")) ){  ///getting first day of the month and see how many days shifted
        case "Mon":$shifter = 0; break;
        case "Tue":$shifter = 1; break;
        case "Wed":$shifter = 2; break;
        case "Thu":$shifter = 3; break;
        case "Fri":$shifter = 4; break;
        case "Sat":$shifter = 5; break;
        case "Sun":$shifter = 6; break;
        default : break;
    }

    $started = false;
    for($i = 0 ; $i < (6 * 7); $i++){
        $ind = $i - $shifter;
        $d = date( "d", strtotime("$yr-$mn +$ind days"));
        if ($ind < 0 || $ind + 1 != (int)$d) continue;
        else $daysTime[$i]['day'] = $d;
        
    }

    foreach($data as $id => $timeObj){
        if( (int)date( "Y", strtotime($timeObj["time"])) === (int)$_GET["yr"] && 
                    (int)date( "m", strtotime($timeObj["time"])) === (int)$_GET["mn"] )
            {
           
            $time = date( "y-m-d H:i", strtotime($timeObj["time"]));
            $slot_s = $timeObj["slots"];
            $slot_u =  $timeObj["users"];


            $dayId = (int)date( "d", strtotime($timeObj["time"]));
            $index = $dayId - 1 + $shifter ;
            $row = floor($index / 7);
            $col = ($index) % 7;
         
            $daysTime[ $col +  $row * 7 ]['times'][] = ["time" => $time, "slots" => $slot_s, "users" => $slot_u];
        }
    }
} else{
    echo "ERROR: Invalid Arguments!";
}
?>

<tr>
    <th>Mon</th>
    <th>Tue</th>
    <th>Wed</th>
    <th>Thu</th>
    <th>Fri</th>
    <th>Sat</th>
    <th>Sun</th>
</tr>
<?php for($r = 0 ; $r < $calRows ; $r++):?>
    <tr>
        <?php for($c = 0 ; $c < $calCol ; $c++):?>
            <?php if($daysTime[ $c +  $r * 7 ]['day'] != '') : ?>
                <td>
                <h3 class="day-number"><?= $daysTime[ $c +  $r * 7 ]['day']?></h3>
                    <?php foreach($daysTime[ $c +  $r * 7 ]['times'] as $t): ?>
                        <?php
                            $dd = date("d", strtotime($t["time"]));
                            $mm = date("m", strtotime($t["time"]));
                            $yy = date("Y", strtotime($t["time"]));
                            $hr = date("H", strtotime($t["time"]));
                            $mn = date("i", strtotime($t["time"]));
                            $lin = "dd=".$dd . "&mm=".$mm ."&yy=".$yy ."&hr=".$hr ."&mn=".$mn;
                            
                            if(strlen($user["time"]) > 0 || strtotime(date("y-m-d")) >= strtotime($t["time"]) ){
                                echo "<span class=\"span-link\">" . $hr . ":". $mn ." ". count($t["users"]) . "/".$t["slots"]. "</span>";
                            }else{
                                if(count($t["users"]) < $t["slots"]){
                                    echo "<a  href=\"pure_backend/time_redirect.php?$lin\" class=\"time-link free-slots\">" . $hr . ":". $mn ." ". count($t["users"]) . "/".$t["slots"]. "</a>";
                                }else{
                                    echo "<a href=\"pure_backend/time_redirect.php?$lin\" class=\"time-link full-slots\">" . $hr . ":". $mn ." ". count($t["users"]) . "/".$t["slots"]. "</a>";
                                }
                                
                            }
                            
                        ?>
                        
                    <?php endforeach?>
                </td>
            <?php else: ?>
                <td class="disabled-cell"> </td>
            <?php endif ?>
        <?php endfor?>  

    </tr>
<?php endfor?>

