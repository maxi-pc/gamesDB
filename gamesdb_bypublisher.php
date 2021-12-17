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
				<li><a class="nav-link" href="gamesdb_byplatform.php">View Games by Platform</a></li>
				<li><a class="nav-link active" href="gamesdb_bypublisher.php">View Games by Publisher</a></li>
				<li><a class="nav-link" href="gamesdb_bydeveloper.php">View Games by Developers</a></li>
			</ul>
			
			<h1 class="main-title">Search by Game Publishers</h1>

			<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
				<ul class="clearfix search-opt">
	        		<li class="fLeft">
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
	 				</li>
        			
  					<li class="fLeft">
	        			<input type="submit" name="submit" value="Submit"/>
	        		</li>
        		</ul>
			</form>
    	
    		<?php

			if(isset($_POST['submit'])){
				//extract($_REQUEST);
				//InsertGame();
				DisplayGames();
			}
	
			function DisplayGames(){
		
				extract($_REQUEST);
	
				// require db connection
				require "gamesdb_connect.php";
	
	  			// array of plataforms available
	  			$platformArray = array('platPC','platPS3','platPS4','plat360','platOne','platWii','platWiiU','platSwitch');

      			if(isset( $_POST['publist'])) {
	  				// prepare, execute and fetch the query
	  				$gamesQuery = $conn->prepare("SELECT GAME_NAME, GAME_REL_YEAR, GAME_SCORE FROM `games` AS G 
									INNER JOIN publishers AS P ON G.PUB_ID = P.PUB_ID
									WHERE P.PUB_ID = ". $_POST['publist']);
					$gamesQuery->execute();
					$gamesResults = $gamesQuery->fetchAll(PDO::FETCH_ASSOC);
			
					//var_dump($gamesResults);

					// print the table header
					print "<table class='results-table'>";
					print "<th>Game Name</th><th>Release Year</th><th>Score</th>";
			
					// counter and loop to print all results					
					$i = 0;
					while ($i < count($gamesResults)){
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
						
						print "</tr>";
						$i++;
					}
					print "</table>";			
					} else {
						//debug
						print "No Publisher Selected!";
					}
				}
			?>
		</div>
  	</body>
</html>
