<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}






$time1 =strtotime($_POST['due_Date']);

$time2= date("d-m-Y");
if(strtotime($time2) < $time1)
{

	echo 'Survey Date is over';
	exit;
}
require_once "config.php";
$flag = 0;
$sql = 'SELECT name FROM '.trim($_POST['tname']).' WHERE name="'.trim($_POST['Name']).'";';
echo $sql;
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->store_result();
$rowCount=$stmt->num_rows;
if($rowCount>=$_POST['Submission'] && $_POST['Submission']!=0)
{
	echo 'You have filled the forms '.$_POST['Submission'].' times, no more submissions from you';
	$stmt->close();	
	exit;
}
$stmt->close();
$sql = "INSERT INTO `".trim($_POST['tname'])."` ";
$sql_columns = " (name,email,";
$sql_values = ' VALUES ("'.$_POST["Name"].'","'.$_POST["Email"].'",';
$index = 0;
$flag = 0;
foreach ($_POST as $key => $value) 
{
	if($key[0]=='Q')
	{
		$question_number = 'Q';
		$index++;
		$question_number = 'Q'.$index;
		$sql_columns = $sql_columns.$question_number.",";
		if(gettype($value)=="array")
		{
			$sql_values = $sql_values.'"';
			for ($i=0; $i < count($value); $i++) 
			{
				$sql_values = $sql_values.($value[$i]).',';
			}
			$sql_values = $sql_values.'",';
			

		}
		else
		{
			$sql_values = $sql_values.'"'.($value).'",';
		}
	}
}
$sql_values[strlen($sql_values)-1] = ')';
//$sql_values = $sql_values.'")';
$sql_columns[strlen($sql_columns)-1] = ')';
$sql = $sql.$sql_columns.$sql_values;
$sql = $sql.';';
echo $sql;
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->close();
echo "your responses added successfully thanks!";
//header('location : welcome.php');
//exit();
?>