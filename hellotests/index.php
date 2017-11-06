<?php
include 'values.php';
?>

<!DOCTYPE html>
<html>
<title>PHPTest</title>
	<body>
	
		<h1> Testing </h1>
	
		<?php
			$values = new values();
			$name = "Sami";
			
			echo "Hello world";
			
			function test(){
				$x = 5;
				return $x;
			}
			
			function doubleValue($i){
				return $i * 2;
			}
			
			function returnFalse(){
				return false;
			}
		?>
		
		<h3>Server name: <?php echo $_SERVER["SERVER_NAME"]; ?></h3>
		<h3><?php echo $name; ?></h3>
		<h3><?php echo test(); ?></h3>
		<h3><?php echo doubleValue(2); ?></h3>
		<h3><?php echo $values->valutesting(); ?></h3>
		<pre><?php var_dump($values->ArrayTest()); ?></pre>
		
		<button onclick="myFunction()" id="javabtn" value="jsbtn">JSALERT</button>

		<script>
		function myFunction() {
			alert("I am an alert box!");
		}
		</script>
		
		<br>
		<br>

		<form method="post" action="results.php" id="update_form">
			 <label for="user_name">Name</label>
			 <input type="text" name="user[name]" id="user_name" />
			 <label for="user_email">Email</label>
			 <input type="text" name="user[email]" id="user_email" />
			 <label for="user_gender">Gender</label>
			 <select id="user_gender" name="user[gender]">
				  <option value="m">Male</option>
				  <option value="f">Female</option>
			 </select>
			 <input type="checkbox" name="Animal" value="Cat"> I have a cat
			 <br>
			 <br>
			 <input type="submit"  name="submitButton" value="Update" />
		</form>
		
	</body>
</html>