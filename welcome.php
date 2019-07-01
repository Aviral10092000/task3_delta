<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        table,tr,td,th {
        	border : 2px solid black;
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
    <div>
        <h1>
            <center>
                Create survey forms
                <br>
                <br>
                <img src="form_img.png">
                <br>
                <a href="form_templates.php" class="btn btn-warning"><h2>Create a new survey form</h2></a>
            </center>
        </h1>
    </div>
    <br>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
<br>
<br>
<center><h3>
		Your Forms		
	</h3>
	<table>
		<tr>
		<?php
		require_once "config.php";
		$sql = "SELECT * FROM form_list WHERE userid=".$_SESSION['id'].";";
		$result = $mysqli->query($sql);
		$count = 0;
		while($row = $result->fetch_assoc())
		{
			
			{
				$count++;

				echo '
				
				<td>
				<center>Form link : '.$_SERVER["HTTP_HOST"].$row['link_address'].'</center>
				<center>'.$row['form_title'].'</center>
				<a href = "'.$row['link_address'].'"><img height=50% src="Blank.png" style="border:5px solid black"></a>
				<br>
				<center><a href="'.$row['survey_table'].'.php">See form Responses</a></center>
			</td>
			

			
			';

			if($count%4==0)
			{
				echo '</tr><tr>';
			}
			}
		}
		?>
	</tr>
	</table>
</center>

</body>
</html>