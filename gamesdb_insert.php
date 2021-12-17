<html>
    <head>
        <title>Games CMS</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/general.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>
    <style>
        .checked {display:none;}
        #newinput:checked ~ .unchecked {display:none;}
        #newinput:checked ~ .checked {display:block;}
    </style>
    <body>
        <div class="container">
            <ul class="nav">
                <li><a class="nav-link" href="index.html">Home</a></li>
                <li><a class="nav-link active" href="gamesdb_insert.php">Insert Data to Database</a></li>
                <li><a class="nav-link" href="gamesdb_byplatform.php">View Games by Platform</a></li>
                <li><a class="nav-link" href="gamesdb_bypublisher.php">View Games by Publisher</a></li>
                <li><a class="nav-link" href="gamesdb_bydeveloper.php">View Games by Developers</a></li>
            </ul>
      
            <h1 class="main-title">Add a new game</h1>

            <form class="insert-form" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
                <fieldset class="ist-game">
                    <div class="each-line">
                        <label>Game Name:</label>
                        <input type="text" name="gameName" value="">
                    </div>

                    <div class="each-line">
                        <label>Release Year (YYYY):</label>
                        <input type="text" name="relYear" value="">
                    </div>

                    <div class="each-line">
                        <label>Game Score (##.#)</label>
                        <input type="text" name="gameScore" value="">
                    </div>

                    <div class="each-line">
                        <label><strong>Publisher</strong></label>
                        <select name="publist">
                            <option disabled="disabled" selected="selected" value="notselected">Select a Publisher</option>
                            <?php
                                require "gamesdb_connect.php"; 
                        
                                $pubssList = $conn->prepare('SELECT PUB_NAME, PUB_ID FROM publishers');
                                $pubssList->execute();
                        
                                while($row = $pubssList->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="'.$row['PUB_ID'].'">'.$row['PUB_NAME'].'</option>';
                                }
                            ?>
                        </select>
                    </div>

                    <div class="each-line">
                        <label><strong>Developers</strong></label>
                        <select name="devlist">
                            <option disabled="disabled" selected="selected" value="notselected">Select a Developer</option>
                            <?php
                                                   require "gamesdb_connect.php"; 
                        
                                $devsList = $conn->prepare('SELECT DEV_NAME, DEV_ID FROM developers');
                                $devsList->execute();
                                while($row = $devsList->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="'.$row['DEV_ID'].'">'.$row['DEV_NAME'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </fieldset>

                <fieldset class="ist-dev">
                    <div class="add-new-dev">
                        <input type="checkbox" class="newDevPuB" id="newinput" name="newDevPuB" value ="1" onchange="valueChanged()"/>Create new publisher / developer
                    </div> 

                    <div id="newInput" class="newInput">   
                        <div class="each-line">
                            <label>Developers Name</label>
                            <input type="text" name="devName" value="">
                        </div>

                        <div class="each-line">
                            <label>Developers Location (Country)</label>
                            <input type="text" name="devLoc" value="">
                        </div>

                        <div class="each-line">
                            <label>Publisher Name</label>
                            <input type="text" name="pubName" value="">
                        </div>

                        <div class="each-line">
                            <label>Publisher Location (Country)</label>
                            <input type="text" name="pubLoc" value="">
                        </div>
                    </div>
                </fieldset>
  		
                <fieldset>
                    <label>Platforms</label>
                    <div class="clearfix check-boxes">
                        <div class="fLeft">
                            <input type="checkbox" name="device[]" value ="platPC"/>PC<br/>
                            <input type="checkbox" name="device[]" value ="platPS3"/>Playstation 3<br/>
                            <input type="checkbox" name="device[]" value ="platPS4"/>Playstation 4<br/>
                            <input type="checkbox" name="device[]" value ="plat360"/>Xbox 360
                        </div>
                        <div class="fLeft">
                            <input type="checkbox" name="device[]" value ="platOne"/>Xbox One<br/>
                            <input type="checkbox" name="device[]" value ="platWii"/>Wii<br/>
                            <input type="checkbox" name="device[]" value ="platWiiU"/>Wii U<br/>
                            <input type="checkbox" name="device[]" value ="platSwitch"/>Switch
                        </div>
                    </div>
                </fieldset>
                
                <fieldset>
                    <label>Genres</label>
                    <div class="clearfix check-boxes">
                        <div class="fLeft">
                            <input type="checkbox" name="genre[]" value ="genAction"/>Action<br/>
                            <input type="checkbox" name="genre[]" value ="genFPS"/>First Person Shooter<br/>
                            <input type="checkbox" name="genre[]" value ="genAdv"/>Adventure<br/>
                            <input type="checkbox" name="genre[]" value ="genMMO"/>Massively-Multiplayer Online<br/>
                            <input type="checkbox" name="genre[]" value ="genRacing"/>Racing<br/>
                            <input type="checkbox" name="genre[]" value ="genRPG"/>Role Playing Games
                        </div>
                        <div class="fLeft">
                            <input type="checkbox" name="genre[]" value ="genRTS"/>Real Time Stategy<br/>
                            <input type="checkbox" name="genre[]" value ="genSim"/>Simulation<br/>
                            <input type="checkbox" name="genre[]" value ="genSport"/>Sports<br/>
                            <input type="checkbox" name="genre[]" value ="genMOBA"/>MOBA<br/>
                            <input type="checkbox" name="genre[]" value ="genOpenWorld"/>Open World<br/>     
                            <input type="checkbox" name="genre[]" value ="genSurv"/>Survival
                        </div>
                    </div>
                </fieldset>
          		<fieldset class="clearfix" style="width: 420px;">
                    <input type="submit" name="submit" class="fLeft" value="Submit"/>
                    <input type="reset" name="reset" class="fRight" value="Reset"/>
                </fieldset>
            </form>
    	
            <?php
                if(isset($_POST['submit'])){
                   extract($_REQUEST);
                   InsertGame();
                }
	
                // function to inser a new game
                function InsertGame(){
                    $gameVali = array('gameName','relYear','gameScore');
                    $newDevPubVali = array('newDevPuB');
                    $genPlatPubDevVali = array('device','genre','publist','devlist');
                    $newDevPubCheck = array('devName','devLoc','pubName','pubLoc');
	
	               //$myValidation['gameName'];
	
                	extract($_REQUEST);
                	$vali = array();
	
                    if(isset($_POST['newDevPuB'])) {  
                        $vali[ 'newDevPuB' ] = 1; 
                    } else { 
                        $vali[ 'newDevPuB' ] = 0; 
                    }
        
                    foreach($gameVali as $selection ) {
                        if(isset($_POST[$selection]) && $_POST[$selection] != "") {
                            $vali[ $selection ] = 1;
                        } else  {
                            $vali[ $selection ] = 0;
                        }
                    }
          
                    foreach($genPlatPubDevVali as $selection ) {
                        if(isset($_POST[$selection])) {
                            $vali[ $selection ] = 1;
                        } else {
                            $vali[ $selection ] = 0;
                        }
                    }
          
                    foreach($newDevPubCheck as $selection ) {
                        if(isset($_POST[$selection]) && $_POST[$selection] != "")  {
                            $vali[ $selection ] = 1;
                        } else {
                            $vali[ $selection ] = 0;
                        }
                    }

                    print "DEBUG STUFF (VALIDITY CHECK AND SUCH)";
                    print_r($vali);
    
	               require "gamesdb_connect.php";

	               if($vali['gameName'] == 1 && $vali['relYear'] == 1 && $vali['gameScore'] == 1 && $vali['newDevPuB'] == 0 && $vali['device'] == 1 && $vali['genre'] == 1 && $vali['publist'] == 1 && $vali['devlist'] == 1 && $vali['devName'] == 0 && $vali['devLoc'] == 0 && $vali['pubName'] == 0 && $vali['pubLoc'] == 0) {
                        $genreID = InsertGenre();
                        $platID = InsertPlatform();

                        try  { 
                            $DBH = $conn->prepare("INSERT INTO games  ( GAME_NAME, GAME_REL_YEAR, PUB_ID, DEV_ID, GEN_ID, PLAT_ID, GAME_SCORE  )
                                                    VALUES (:GAME_NAME, :GAME_REL_YEAR, :PUB_ID, :DEV_ID, :GEN_ID, :PLAT_ID, :GAME_SCORE)");
                            $DBH->bindParam(':GAME_NAME',     $gameName);
                            $DBH->bindParam(':GAME_REL_YEAR', $relYear);
                            $DBH->bindParam(':PUB_ID',        $_POST['publist']);
                            $DBH->bindParam(':DEV_ID',        $_POST['devlist']);
                            $DBH->bindParam(':GEN_ID',        $genreID);
                            $DBH->bindParam(':PLAT_ID',       $platID);
                            $DBH->bindParam(':GAME_SCORE',    $gameScore);
                            $DBH->execute();
                        } 
                        catch(PDOException $e) {
                            echo 'Connection failed: ' . $e->getMessage();
                        }
                    }
                    elseif($vali['gameName'] == 1 && $vali['relYear'] == 1 && $vali['gameScore'] == 1 && $vali['newDevPuB'] == 1 && $vali['device'] == 1 && $vali['genre'] == 1 && $vali['publist'] == 0 && $vali['devlist'] == 0 && $vali['devName'] == 1 && $vali['devLoc'] == 1 && $vali['pubName'] == 1 && $vali['pubLoc'] == 1) {		
                        $genreID = InsertGenre();
                        $platID = InsertPlatform();
                        $devID = InsertDeveloper();
                        $pubID = InsertPublisher();

                        print $devID. "<br/>";
                        print $pubID. "<br/>";
		
                        try { 
                            $DBH = $conn->prepare("SET FOREIGN_KEY_CHECKS=0;INSERT INTO games  ( GAME_NAME, GAME_REL_YEAR, PUB_ID, DEV_ID, GEN_ID, PLAT_ID, GAME_SCORE  ) VALUES (:GAME_NAME, :GAME_REL_YEAR, :PUB_ID, :DEV_ID, :GEN_ID, :PLAT_ID, :GAME_SCORE);SET FOREIGN_KEY_CHECKS=1;");
                            $DBH->bindParam(':GAME_NAME',     $gameName);
                            $DBH->bindParam(':GAME_REL_YEAR', $relYear);
                            $DBH->bindParam(':PUB_ID',        $pubID);
                            $DBH->bindParam(':DEV_ID',        $devID);
                            $DBH->bindParam(':GEN_ID',        $genreID);
                            $DBH->bindParam(':PLAT_ID',       $platID);
                            $DBH->bindParam(':GAME_SCORE',    $gameScore);
                            $DBH->execute();
                        } 
                        catch(PDOException $e) { 
                            echo 'Connection failed: ' . $e->getMessage(); 
                        }
                    } else {  
                        echo "<h1>Please make sure you input data to all the fields.  (if you checked create new publisher / developer then do not touch the dropdown menu and instead type new information.</h1>";
                    }
                }
	
                // function to add a new developer
                function InsertDeveloper(){
                    extract($_REQUEST);
                    require "gamesdb_connect.php";
	
                    if($devName != NULL && $devLoc != NULL) {
                        try {
                            $DBH = $conn->prepare("INSERT INTO developers ( DEV_NAME, DEV_LOCATION )
                                                    VALUES (:DEV_NAME, :DEV_LOCATION)");
                            $DBH->bindParam(':DEV_NAME',     $devName);
                            $DBH->bindParam(':DEV_LOCATION', $devLoc);
                            $DBH->execute();
                            $id = $conn->lastInsertId();
                            return $id;
                        } 
                        catch(PDOException $e) {
                            echo 'Connection failed: ' . $e->getMessage();
                        }
                    } else {
                        echo "No Developer Info Inserted...";
                    }
                }
	
                // Function to insert a new publisher
                function InsertPublisher(){
                    extract($_REQUEST);
                    require "gamesdb_connect.php";
	
                    if($pubName != NULL && $pubLoc != NULL) {
                        try {
                            $DBH = $conn->prepare("INSERT INTO publishers ( PUB_NAME, PUB_LOCATION )
                                                    VALUES (:PUB_NAME, :PUB_LOCATION)");
                            $DBH->bindParam(':PUB_NAME',     $pubName);
                            $DBH->bindParam(':PUB_LOCATION', $pubLoc);
                            $DBH->execute();
                            $id = $conn->lastInsertId();
                            return $id;
                        } 
                        catch(PDOException $e) {
                            echo 'Connection failed: ' . $e->getMessage();
                        }
                    } else {  
                        echo "No Publisher Info Inserted...";
                    }
                }
	
                // function to insert a new genre
                function InsertGenre(){
                    extract($_REQUEST);	
                    require "gamesdb_connect.php";
	
                    $genreArray = array('genAction','genFPS','genAdv','genMMO','genRacing','genRPG','genRTS','genSim','genSport','genMOBA','genOpenWorld','genSurv');

                    if(isset( $_POST['genre'])) {
                        $values = array();
                        foreach($genreArray as $selection ) {
                            if(in_array($selection, $_POST['genre'])) {
                                $values[ $selection ] = 1;
                            } else {
                                $values[ $selection ] = 0;
                            }
                        }
                        // print_r($values);
                        try {
                            $DBH = $conn->prepare("INSERT INTO genres ( GENRE_ACTION, GENRE_FPS, GENRE_ADVENTURE, GENRE_MMO, GENRE_RACING, GENRE_RPG, GENRE_RTS, GENRE_SIM, GENRE_SPORTS, GENRE_MOBA, GENRE_OPENWORLD, GENRE_SURVIVAL )
                                                    VALUES (:GENRE_ACTION, :GENRE_FPS, :GENRE_ADVENTURE, :GENRE_MMO, :GENRE_RACING, :GENRE_RPG, :GENRE_RTS, :GENRE_SIM, :GENRE_SPORTS, :GENRE_MOBA, :GENRE_OPENWORLD, :GENRE_SURVIVAL)");
                            $DBH->bindParam(':GENRE_ACTION',    $values['genAction']);
                            $DBH->bindParam(':GENRE_FPS',       $values['genFPS']);
                            $DBH->bindParam(':GENRE_ADVENTURE', $values['genAdv']);
                            $DBH->bindParam(':GENRE_MMO',       $values['genMMO']);
                            $DBH->bindParam(':GENRE_RACING',    $values['genRacing']);
                            $DBH->bindParam(':GENRE_RPG',       $values['genRPG']);
                            $DBH->bindParam(':GENRE_RTS',       $values['genRTS']);
                            $DBH->bindParam(':GENRE_SIM',       $values['genSim']);
                            $DBH->bindParam(':GENRE_SPORTS',    $values['genSport']);
                            $DBH->bindParam(':GENRE_MOBA',      $values['genMOBA']);
                            $DBH->bindParam(':GENRE_OPENWORLD', $values['genOpenWorld']);
                            $DBH->bindParam(':GENRE_SURVIVAL',  $values['genSurv']);
                            $DBH->execute();
                            $id = $conn->lastInsertId();
                            return $id;
                        }
                        catch(PDOException $e) {
                            echo 'Connection failed: ' . $e->getMessage();
                        }
                    } else { 
                        echo 'No Genre Selected...';
                    }
                }

                // function to insert a new plataform
                function InsertPlatform(){
                    extract($_REQUEST);
                    require "gamesdb_connect.php";
	
                    $platformArray = array('platPC','platPS3','platPS4','plat360','platOne','platWii','platWiiU','platSwitch');

                    if(isset( $_POST['device'])) {  
                        $values = array();
                        foreach($platformArray as $selection ) {
                            if(in_array($selection, $_POST['device'])) {
                                $values[ $selection ] = 1;
                            } else {
                                $values[ $selection ] = 0;
                            }
                        }
                        // print_r($values);
                        try { 
                            $DBH = $conn->prepare("INSERT INTO platforms ( PLAT_PC, PLAT_PS3, PLAT_PS4, PLAT_XBOX360, PLAT_XBOXONE, PLAT_WII, PLAT_WIIU, PLAT_SWITCH )
                                                    VALUES (:PLAT_PC, :PLAT_PS3, :PLAT_PS4, :PLAT_XBOX360, :PLAT_XBOXONE, :PLAT_WII, :PLAT_WIIU, :PLAT_SWITCH)");
                            $DBH->bindParam(':PLAT_PC',      $values['platPC']);
                            $DBH->bindParam(':PLAT_PS3',    $values['platPS3']);
                            $DBH->bindParam(':PLAT_PS4',    $values['platPS4']);
                            $DBH->bindParam(':PLAT_XBOX360',$values['plat360']);
                            $DBH->bindParam(':PLAT_XBOXONE',$values['platOne']);
                            $DBH->bindParam(':PLAT_WII',    $values['platWii']);
                            $DBH->bindParam(':PLAT_WIIU',       $values['platWiiU']);
                            $DBH->bindParam(':PLAT_SWITCH', $values['platSwitch']);
                            $DBH->execute();
                            $id = $conn->lastInsertId();
                            return $id;
                        }
                        catch(PDOException $e) {
                            echo 'Connection failed: ' . $e->getMessage();
                        }
                    } else {
                        echo 'No Platforms selected...'; 
                    }
                }
            ?>
        </div>
        
        <!-- script to show/hide the dev/pub text fields-->
        <script>
            $(".newInput").hide();
            $(".newDevPuB").click(function() {
                if($(this).is(":checked")) {
                    $(".newInput").show("fast");
                } else {
                    $(".newInput").hide("fast");
                }
            });
        </script>  
    </body>
</html>
