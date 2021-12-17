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
				<li><a class="nav-link" href="gamesdb_insert.php">Insert Data to Database</a></li>
				<li><a class="nav-link active" href="gamesdb_byplatform.php">View Games by Platform</a></li>
				<li><a class="nav-link" href="gamesdb_bypublisher.php">View Games by Publisher</a></li>
				<li><a class="nav-link" href="gamesdb_bydeveloper.php">View Games by Developers</a></li>
			</ul>
			
			<h1 class="main-title">Search by Game Platforms</h1>

	  		<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
	        	<ul class="clearfix search-opt">
	        		<li class="fLeft">
	        			<input type="checkbox" name="device[]" value ="platPC"/>PC
	        		</li>
	        		<li class="fLeft">
	        			<input type="checkbox" name="device[]" value ="platPS3"/>Playstation 3
	        		</li>
	        		<li class="fLeft">
	        			<input type="checkbox" name="device[]" value ="platPS4"/>Playstation 4
	        		</li>
	        		<li class="fLeft">
	        			<input type="checkbox" name="device[]" value ="plat360"/>Xbox 360
	        		</li>
	        		<li class="fLeft">
	        			<input type="checkbox" name="device[]" value ="platOne"/>Xbox One
	        		</li>
	        		<li class="fLeft">
	        			<input type="checkbox" name="device[]" value ="platWii"/>Wii
	        		</li>
	        		<li class="fLeft">
	        			<input type="checkbox" name="device[]" value ="platWiiU"/>Wii U
	        		</li>
	        		<li class="fLeft">
	        			<input type="checkbox" name="device[]" value ="platSwitch"/>Switch
	        		</li>
	        		<li class="fLeft">
	        			<input type="submit" name="submit" value="Submit"/>
	        		</li>
	        	</ul>
		  		
			</form>
	    	
	    <?php
		if(isset($_POST['submit'])){
		
			extract($_REQUEST);	
			//InsertGame();
			DisplayGames();	
		}
		
		function DisplayGames(){
			
			extract($_REQUEST);

			// require db connection
			require "gamesdb_connect.php";
		
			// array of plataforms available
		  	$platformArray = array('platPC','platPS3','platPS4','plat360','platOne','platWii','platWiiU','platSwitch');

	    	if(isset( $_POST['device'])) {  
	    		$values = array();
	        	foreach($platformArray as $selection ) {
	        		if(in_array($selection, $_POST['device'])) {
	        			$values[ $selection ] = 1;
	        		}
	             	else {
	             		$values[ $selection ] = 0;
	             	}
	          	}
	          
				// print_r($values);

          		// array to get values from db
				$query = ("SELECT GAME_NAME, GAME_REL_YEAR, GAME_SCORE, DEV_NAME, PUB_NAME FROM `games` AS G
							INNER JOIN developers AS D ON G.DEV_ID = D.DEV_ID
							INNER JOIN publishers AS P ON G.PUB_ID = P.PUB_ID
							INNER JOIN platforms AS PL ON G.PLAT_ID = PL.PLAT_ID
							WHERE 1 IN ( ");
										
										
				$flag = 0;
				
				// change the query to add the plataform selected to it
				if($values['platPC'] == 1) {
					$query .= " PLAT_PC ";
					$flag = 1;
				}

				if($values['platPS3'] == 1) {
	   				if($flag == 1) {
	        			$query .= " AND ";
	   				}
	   				$query .= " PLAT_PS3 ";
	   				$flag = 1;
				}
				
				if($values['platPS4'] == 1) {
	   				if($flag == 1) {
	        			$query .= " AND ";
	   				}
	   				$query .= " PLAT_PS4 ";
	   				$flag = 1;
				}
				
				if($values['plat360'] == 1) {
					if($flag == 1){
						$query .= " AND ";
					}	
					$query .= " PLAT_XBOX360 ";
					$flag = 1;
				}

				if($values['platOne'] == 1) {
					if($flag == 1) {
						$query .= " AND ";
					}
					$query .= " PLAT_XBOXONE ";
					$flag = 1;
				}

				if($values['platWii'] == 1) {
					if($flag == 1) {
						$query .= " AND ";
					}    
					$query .= " PLAT_WII ";
					$flag = 1;
				}

				if($values['platWiiU'] == 1) {
					if($flag == 1) {
						$query .= " AND ";
					}    
					$query .= " PLAT_WIIU ";
					$flag = 1;
				}

				if($values['platSwitch'] == 1) {
					if($flag == 1) {
						$query .= " AND ";
					}     
					$query .= " PLAT_SWITCH ";
				}
		
	   			$query .= ")";
	   
	  			// print $query;
	   
	   			// prepare, execute and fetch the query
				$gamesQuery = $conn->prepare($query);
				$gamesQuery->execute();
				$gamesResults = $gamesQuery->fetchAll(PDO::FETCH_ASSOC);
			
				// print the table header
				print "<table class='results-table'>";
				print "<th>Game Name</th><th>Release Year</th><th>Score</th><th>Developer Name</th><th>Publisher Name</th>";
				
				// counter and loop to print all results
				$i = 0;
				while ($i < count($gamesResults)) {
					print "<tr>";
					
					print "<td>";
					print $gamesResults[$i]['GAME_NAME'];
					print "</td>";
					
					print "<td>";
					print $gamesResults[$i]['GAME_REL_YEAR'];
					print "</td>";
					
					print "<td>";
					print $gamesResults[$i]['GAME_SCORE'];
					print "</td>";
					
					print "<td>";
					print $gamesResults[$i]['DEV_NAME'];
					print "</td>";
					
					print "<td>";
					print $gamesResults[$i]['PUB_NAME'];
					print "</td>";
					
					print "</tr>";
					$i++;
				}
				print "</table>";
			}
		}
		?>
	</div>
  </body>
</html>
