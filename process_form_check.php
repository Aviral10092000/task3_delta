<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "config.php";
$id = $_SESSION['id'];
$origDate = $_POST['due_Date'];
$date = str_replace('/', '-', $origDate );
$newDate = date("Y-m-d", strtotime($date));
echo $newDate;
$sql = "INSERT INTO form_list (userid,form_title,link_address,survey_table,submission,valid_till) VALUES (?,?,?,?,?,?);";
$stmt = $mysqli->prepare($sql);
$ud = 'abcd';
$stmt->bind_param('isssis', $id,$_POST['Title'],$ud,$ud,$_POST['Submission'],$newDate);
echo $_POST['Submission'];
$stmt->execute();
$stmt->close();
$lastid = $mysqli->insert_id;
$flag = 0;
$value_posted = 0;
$storage = array();
$myfile = fopen("surveyform".$lastid.".php", "w");
$columns = [];
$txt = '<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html id="html">
<head>
	<title>Title</title>

	<style>
div {
	display: inline-block;
	height: auto;
	width: 100%;
}
table {
	border : 2px solid black;
}
</style>
<script type="text/javascript">
function validateEmail(email)
{

var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
if(!email.value.match(mailformat))
{
	alert("You have entered an invalid email address!");
	this.focus()
}
function validateName(name)
{
	if(name.value=="")
	{
		alert("Name cannot be blank");
		name.focus();
	}
}
}
</script>

</head>

<body>
	<center><form id="data" name="data" method="post" action="collect_data_adavance.php"><div id="title"><table><tr><td>Form Title :</td><td> '.$_POST['Title'].'</td></tr><tr><td>Form Description :</td><td> '.$_POST["Description"].'</td></tr><tr><td>Name:</td><td><input type="text" name="Name" onchange="validateName(this)"></td></tr><tr><td>Email:</td><td><input type="Text" name="Email" onchange="validateEmail(this)"></td></tr><tr><td>Due date:</td><td>'.$_POST['due_Date'].'</td></tr><tr><td><input type="hidden" name="Submission" value="'.$_POST['Submission'].'"></td><td><input type="hidden" name="due_Date" value="'.$_POST['due_Date'].'"></td></tr></table></div>';
$question_number = 1;
foreach ($_POST as $key => $value)
{
	echo "$key=>$value";
	echo '<br>';
	if($key[0]=='Q')
	{

		$pos = strpos($key, 'select');

		if($pos)
		{

				if($value=='Text')
				{
					$txt = $txt.'<div><table><tr><td>'.$_POST[$storage[0]].'<br><div><br><input type="text" name="Q'.$question_number.'_A" value="text"></div>         
					</td></tr></table></div>';
				}
				else if($value=='Select')
				{
					$txt = $txt.'<div><table><tr><td>'.$_POST[$storage[0]].'<br><div><br>';
					for ($i=1; $i <count($storage);$i++) 
					{ 
						$txt = $txt.'<input name="Q'.$question_number.'_A" type="radio" value="'.$_POST[$storage[$i]].'">'.$_POST[$storage[$i]].'<br>';
					}
					$txt = $txt.'</div></td></tr></table></div>';
				}
				else if($value=='File')
				{
					$txt = $txt.'<div><table><tr><td>'.$_POST[$storage[0]].'<br><div><br><input type="file" name="Q'.$question_number.'_A" value="text"></div>         
					</td></tr></table></div>';
				}
				else if($value=='Checkbox')
				{
					$index = 1;
					$txt = $txt.'<div><table><tr><td>'.$_POST[$storage[0]].'<br><div><br>';
					for ($i=1; $i <count($storage);$i++) 
					{ 
						$txt = $txt.'<input name="Q'.$question_number.'_A[]" type="checkbox" value = "'.$_POST[$storage[$i]].'">'.$_POST[$storage[$i]].'<br>';
						$index++;
					}
					$txt = $txt.'</div></td></tr></table></div>';
				}
			$storage = [];	
			array_push($columns, 'Q'.$question_number);
			$question_number++;

		}
		else
		{
			array_push($storage, $key);

		}
	}
}
$txt = $txt.'<button type="submit">Submit</button><input type="hidden" value="surveytb'.$lastid.'" name="tname"></form></center></body></html>';
fwrite($myfile, $txt);
fclose($myfile);


$sql = 'CREATE TABLE surveytb'.$lastid.' (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL,';

for ($i=0; $i < count($columns); $i++) 
{ 
	$sql = $sql.$columns[$i].' VARCHAR(100) NOT NULL,';
}

$sql = $sql.' reg_date TIMESTAMP);';
$file_name = "/surveyform".$lastid.".php";
$actual_link = dirname("{$_SERVER['REQUEST_URI']}").$file_name;
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$stmt->close();
$sql =	'UPDATE form_list SET link_address = ?,survey_table = ? WHERE id='.$lastid.';';
$stmt = $mysqli->prepare($sql);
$st = 'surveytb'.$lastid;
$stmt->bind_param('ss',$actual_link,$st);
$stmt->execute();
$stmt->close();
$mysqli->close();
$myfile = fopen('surveytb'.$lastid.'.php','w');
$txt = '<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;

}
require_once "config.php";
?><html>
<body>
<center>
<table border="1px">
<tr>
<?php
	$sql = "SHOW COLUMNS FROM surveytb'.$lastid.';";
	$res = $mysqli->query($sql);
	$str_col = array();
	while($row = $res->fetch_assoc())
	{
		echo "<td>";
    	$columns = $row["Field"];
    	echo $columns;
    	echo "</td>";
    	array_push($str_col,$columns);
	}
?>
</tr>	
<?php
require_once "config.php";
$query = "SELECT * FROM surveytb'.$lastid.';";
$count_row = $mysqli->insert_id;
$result = $mysqli->query($query);
if ($result->num_rows > 0)
{

    // output data of each row
    while($row = $result->fetch_assoc()) {
    	echo "<tr>";
        for($i=0;$i<count($str_col);$i++)
        {
        	echo "<td>";
        	echo $row[$str_col[$i]];
        	echo "</td>";
        }
        echo "</tr>";
    }
} 
else
 {
    echo "0 results";
}
?>
</table>
</center>
</body>
</html>';

fwrite($myfile,$txt);
fclose($myfile);

header('location:welcome.php');
exit;
?>