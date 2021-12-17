<html>
	<head>
	</head>
	<body>
	
		<?php
			//define connection parameters
			$servername= "localhost";
			$username = "root";
			$password = "root";
			$dbname = "gamesdb";
			
			try{
				$conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
				$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				//See if connection to database worked
				print "<h1 class='msg-ok'>Database Connecting Succeeded.</h1>";
				
			} catch (PDOException $ex){
				print $e.getMessage;
			}
			
		?>
	</body>
</html>
