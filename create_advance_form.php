<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
<html id='html'>
<head>
<script type="text/javascript">
function validateDate(date) 
{
	
	var dateformat = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
	
  	//Match the date format through regular expression
  	if(date.value.match(dateformat))
  	{
  		var opera1 = date.value.split('/');
  		var opera2 = date.value.split('-');
  		lopera1 = opera1.length;
  		lopera2 = opera2.length;
  		// Extract the string into month, date and year
  		if (lopera1>1)
  		{
  			var pdate = date.value.split('/');
  		}
  		else if (lopera2>1)
  		{
  			var pdate = date.value.split('-');
  		}
  		var dd = parseInt(pdate[0]);
  		var mm  = parseInt(pdate[1]);
  		var yy = parseInt(pdate[2]);
  		// Create list of days of a month [assume there is no leap year by default]
  		var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31];
  		if (mm==1 || mm>2)
  		{
  			if (dd>ListofDays[mm-1])
  			{
  				alert('Invalid date format!');
  				date.focus();
  				return false;
  			}
  		}
  		if (mm==2)
  		{
  			var lyear = false;
  			if ( (!(yy % 4) && yy % 100) || !(yy % 400)) 
  			{
  				lyear = true;
  			}
  			if ((lyear==false) && (dd>=29))
  			{
  				alert('Invalid date format!');
  				date.focus();
  				return false;
  			}
  			if ((lyear==true) && (dd>29))
  			{
  				alert('Invalid date format!');
  				date.focus();
  				return false;
  			}
  		}
  	}
  	else
  	{
  		alert("Invalid date format!");
  		date.focus();
  		return false;
  	}
}
</script>
	<title>Title</title>

<style>
div {
	display: inline-block;
	height: auto;
	width: 100%;
	
}
div.title {
	border : 2px solid black;
	width: 50%;
	background-color: lightgreen;

}
input {
	border-style: outset;
}
table.data {
	width: 50%;
	height : auto;
	border : 2px solid black;
	background-color: lightgreen;
}
textarea {
	width : 100%;
	height: 30%;
}
select {
	width: 100%
}
</style>

</head>

<body bgcolor="purple">
	<center><form id='data' name='data' method="post" action="process_form_check.php"><div id='title' class="title"><table><tr><td>Form Title :</td><td><input type="text" name="Title"></td></tr><br><tr><td>Form Description :</td><td><input type="text" name="Description"></td></tr><tr><td>Name:(This field will be added to the survey form automatically)</td><td><input type="hidden"></td></tr><tr><td>Email : (This field will be added to the survey form automatically)</td><td><input type="hidden"></td></tr><tr><td>Maximum number of submissions per user :</td><td><input type="text" name="Submission" value=0>(0 for any number of submissions per users)</td></tr><tr><td>Due date:</td><td><input type="text" name="due_Date" onchange ="validateDate(this)">(dd/mm/yyyy)</td></tr></table></div><div id='Q1'><table class="data"><tr><td><textarea id='Q1_T' name='Q1_T'>Type your Question here</textarea><br><div id='Q1_A'><br><input id='Q1_A1' type="hidden" value='text'><input type="text" id='Q1_A1_T' value="Write your answer here" name = 'Q1_A1_T'><input type="hidden" id='Q1_d1' value = "Delete Option" onclick="deleteoption(this)"><input type="hidden" id='Q1_o1' value='Add more option' onclick="addoption(this)"></div><button type="button" id='Q1_d' value='button' onclick="delete_question(this)">Delete Question</button></td><td><select  name='Q1_select' onchange="select_option(this)"><option value="Text">Text</option><option value="Select">Select</option><option value="File">File</option><option value="Checkbox">Checkbox</option></select></td></tr></table></div><button id='complete_html' type='button'></button><br><button type='button' onclick="addquestion(this)">Add question</button>&nbsp;&nbsp;&nbsp;<button type='submit'>Save Form</button></form></center>
	<script type="text/javascript">
		var tablet_number = 1;
		function select_option(select_option) {
			var choice = select_option.value;
			var z = select_option.parentNode.previousElementSibling.lastElementChild.previousElementSibling;
			if(choice=='Text')
			{
				z.innerHTML = "<br><input id='Q" + tablet_number + "_A1' type='hidden' value='radio'><input type='text' id='Q" + tablet_number + "_A1_T' name='Q" + tablet_number + "_A1_T' value='text'><input type='hidden' id='Q" + tablet_number + "_d1' value = 'Delete Option' onclick='deleteoption(this)'><input type='hidden' id='Q" + tablet_number + "_o1' value='Add more option' onclick='addoption(this)'>";
			}
			else if(choice=='Select')
			{
				z.innerHTML = "<br><input id='Q" + tablet_number + "_A1' type='radio' value='Select'><input type='text' id='Q" + tablet_number + "_A1_T' name='Q" + tablet_number + "_A1_T' value='text'><input type='hidden' id='Q" + tablet_number + "_d1' value = 'Delete Option' onclick='deleteoption(this)'><input type='button' id='Q" + tablet_number + "_o1' value='Add more option' onclick='addoption(this)'>";
			}
			else if(choice=='File')
			{
				
				z.innerHTML = "<br><input id='Q" + tablet_number + "_A1' type='file' value='File'><input type='hidden' id='Q" + tablet_number + "_A1_T' name ='Q" + tablet_number + "_A1_T' value='text'><input type='hidden' id='Q" + tablet_number + "_d1' value = 'Delete Option' onclick='deleteoption(this)''><input type='hidden' id='Q" + tablet_number + "_o1' value='Add more option' onclick='addoption(this)'>";
			}
			else
			{
				z.innerHTML = "<br><input id='Q" + tablet_number + "_A1' type='checkbox' value='checkbox'><input type='text' id='Q" + tablet_number + "_A1_T' name='Q" + tablet_number + "_A1_T' value='text'><input type='hidden' id='Q" + tablet_number + "_d1'  value = 'Delete Option' onclick='deleteoption(this)''><input type='button' id='Q" + tablet_number + "_o1' value='Add more option' onclick='addoption(this)'>";
			}
		}
		function addoption(input_type) {
				var end = input_type.id.indexOf('o');
				input_type.type = 'hidden';
				var question_answer = parseInt(input_type.id.substr(end+1,input_type.id.length-1));
				question_answer++;
				var create_br = document.createElement('br');
				input_type.parentNode.appendChild(create_br);
				for(var i = 0;i<4;i++)
				{
					var create_input = document.createElement('input');
					var generate_id = input_type.id.substr(0,end-1);
					if(i==0)
					{
						generate_id = generate_id + '_A' + question_answer;
						create_input.setAttribute('id',generate_id);
						if(input_type.parentNode.parentNode.nextElementSibling.firstElementChild.value=='Checkbox')
						create_input.setAttribute('type','checkbox');
						else
						create_input.setAttribute('type','radio');	
						
					}
					if(i==1)
					{

						generate_id = generate_id + '_A' + question_answer +'_T';
						create_input.setAttribute('id',generate_id);
						create_input.setAttribute('type','text');
						create_input.setAttribute('name',generate_id);
					}
					if(i==2)
					{
						generate_id = input_type.id.substr(0,end);
						generate_id = generate_id + 'd' + question_answer;
						create_input.setAttribute('id',generate_id);
						create_input.setAttribute('type','button');
						create_input.setAttribute('value',"Delete Option");
						create_input.setAttribute('onclick','deleteoption(this)');
						
					}
					if(i==3)
					{
						generate_id = input_type.id.substr(0,end);
						generate_id = generate_id + 'o' + question_answer;
						create_input.setAttribute('id',generate_id);
						create_input.setAttribute('type','button');
						create_input.setAttribute('value',"Add more option");
						create_input.setAttribute('onclick','addoption(this)');
						
					}

					input_type.parentNode.appendChild(create_input);
				}

				

			}
			function deleteoption(delete_button) {
				var  end = delete_button.id.indexOf('d');
				var id_option = parseInt(delete_button.id.substr(end+1,delete_button.id.length - 1));
				var parent = delete_button.parentNode;
				var flag = 0;
				if(delete_button.nextElementSibling.nextElementSibling==null)				
					flag = 1;
				if(flag==0)
				{
					for(var i = 4;i>=0;i--)
					{
					
						parent.removeChild(parent.childNodes[5*(id_option-1) + i]);
					}
				}
				if(flag==1)
				{
					
					var count = 0;
					for(var i = parent.childElementCount-1;;i--)
					{
						parent.removeChild(parent.childNodes[i]);
						count++;
						if(count==5)
						{
							break;
						}
					}
					parent.childNodes[parent.childElementCount - 1].type = 'button';
				}
			}
			function htmlcode() {
				console.log(document.getElementById('html').innerHTML);
			}
			function delete_question(button) {
				if(button.parentNode.parentNode.parentNode.parentNode.parentNode.id=='Q1')
				{
					
				}			
				else
				{
					var z = button.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
					for(var i = 0;i<z.childElementCount;i++)
					{
						if(z.childNodes[i].id==button.parentNode.parentNode.parentNode.parentNode.parentNode.id)
						{
							z.removeChild(button.parentNode.parentNode.parentNode.parentNode.parentNode);
						}
					}	
				}
			}
			function addquestion(button) {
				var z = button.previousElementSibling.previousElementSibling.previousElementSibling.id;
				z = parseInt(z.substr(1,z.length - 1));
				z++;
				var create_div = document.createElement('div');		
				create_div.setAttribute('id','Q' + z);
			
				create_div.innerHTML = '<table class="data"><tr><td><textarea id="Q'+z+'_T" name="Q'+z+'_T">Add Question here</textarea><br><div id="Q'+z+'_A"><br><input id="Q'+z+'_A1" type="hidden" value="text"><input type="text" id="Q'+z+'_A1_T" name = "Q'+z+'_A1_T" value="text"><input type="hidden" id="Q'+z+'_d1"  value = "Delete Option" onclick="deleteoption(this)"><input type="hidden" id="Q'+z+'_o1" value="Add more option" onclick="addoption(this)"></div><button type="button" id="Q'+z+'_d" onclick="delete_question(this)">Delete Question</button></td><td><select name="Q'+z+'_select" onchange="select_option(this)"><option value="Text">Text</option><option value="Select">Select</option><option value="File">File</option><option value="Checkbox">Checkbox</option></select></td></tr></table>';
				button.parentNode.insertBefore(create_div,button.previousElementSibling.previousElementSibling);
				tablet_number++;
			}
			
			function change_value(input) 
			{				
				input.setAttribute("value",input.value);
			}
			
			function submitForms() 
			{
				
    			document.getElementById("data").action

			}

	</script>
</html>