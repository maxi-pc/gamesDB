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
				<li><a class="nav-link" href="gamesdb_bypublisher.php">View Games by Publisher</a></li>
				<li><a class="nav-link active" href="gamesdb_bydeveloper.php">View Games by Developers</a></li>
			</ul>
			
			<h1 class="main-title">Search by Game Developers</h1>

			<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
				<ul class="clearfix search-opt">
	        		<li class="fLeft">
						<select name="devlist">
	      					<option disabled="disabled" selected="selected" value="notselected">Select a Developer</option>
		 					<?php
								require "gamesdb_connect.php"; 

		    					$pubssList = $conn->prepare('SELECT DEV_NAME, DEV_ID FROM developers');
		    					$pubssList->execute();
		       					
		       					while($row = $pubssList->fetch(PDO::FETCH_ASSOC)) {
		    						echo '<option value="'.$row['DEV_ID'].'">'.$row['DEV_NAME'].'</option>';
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
      
      			if(isset( $_POST['devlist'])) {
		  			// prepare, execute and fetch the query
		  			$gamesQuery = $conn->prepare("SELECT GAME_NAME, GAME_REL_YEAR, GAME_SCORE FROM `games` AS G 
										INNER JOIN developers AS D ON G.PUB_ID = D.DEV_ID
										WHERE D.DEV_ID = ". $_POST['devlist']);
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
					print "No Developer Selected!";
				}
			}
			?>
		</div>
	</body>
</html>

