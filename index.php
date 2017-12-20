<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="websiteStyle.css">
<title>Reimbursement</title>
<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src-"http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.js"></script>
<!-- <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>
 -->

<!--
Name:  Exchequer Reimbursement Website
Author:  Noam Buckman
Created:  August 2014
Description:  Allows for purchase reimbursements and dinner reimbursements
Directories:
	exchequer/receipts:  All uploaded receipts go here
	exchequer/dinner_pics:  All uploaded dinner pics go here

To Do:
	-Overriding for certain scenarios (e.g Lost receipt)
	-Added budget lines
	-Extra access to non-AEPI Brothers list (i.e. pledges, HouseCorp, etc.)
	-Slideshow of brothers at dinner
	-"Add Receipt Button"

Updates for new exchequer/year:
	1.  Update Brother's list in Dinner Reimbursements (id="brothers")
-->

<script>
//This Script Checks for Errors On Submission
	$(function(){
		$("#submit_reimbursement").click(function(){
			//Assume no errors, erase all errors, red flags, etc.
			var errors = false;
			var receipt_errors=0;
			var field = 0;
			var completed_rows = [];
			$(".errors").remove();
			
			for (var num=1; num<=5; num++){
				$("#cost" + num).attr("class", "default");
				$("#date_reimbursement" + num).attr("class", "default");	
				$("#description" + num).attr("class", "default");	

			}

			//Check each row to see if there are errors
			for (var num=1; num<=5; num++){
				if (receipt_error[num]==1){
					receipt_errors=1;
				}
				var cost = parseFloat($("#cost" + num).val());

				//If all fields in row, ignore (no errors)
				if(($("#description" + num).val() != "") && (cost>0) && 
					($("#date_reimbursement" + num).val() != "") && ($("#receipt" + num).val() != "")) {
					//Row is completely full
					field = 1;  //Tell the form that at list one row is good
					completed_rows.push(num); //Add good row to list completed_rows
				}

				//If entire row is empty, ignore (no errors)
				else if(($("#description" + num).val() == "") && ($("#cost" + num).val() == "") && 
					($("#date_reimbursement" + num).val() == "") && ($("#receipt" + num).val() == "")){
					; //Row is completely empty do nothing
				}
				else{
					
					//Error 1:  Cost field is missing or not a number
  					if (cost>0){
  						;
  					}else{
  						$("#cost" + num).attr("class", "missing_field");	
						errors = true;
  					}

  					//Error 2: Date Field is empty
					if ($("#date_reimbursement" + num).val() == ""){
						$("#date_reimbursement" + num).attr("class", "missing_field");	
						errors = true;					
					}

					//Error 3:  Description Field is empty
					if ($("#description" + num).val() == ""){
						$("#description" + num).attr("class", "missing_field");	
						errors = true;					
					}

					//Error 4:  No receipt has been added
					if ($("#receipt" + num).val() == ""){
						$("#receipt" + num).after('<span class="errors">Receipt Needed</span>');
						errors = true;					
					}

				}
			}

			//Check if there were any upload issues, i.e. size limit and file type
			receipt_errors = $("span").hasClass("receipt_errors");


			//If there are any errors or no complete row, don't submit form
			if (errors == true){			
				$("#submit_reimbursement").after('<span class="errors">Please fix highlighted  fields</span>');
				return false;
			}else if (field==0){
				$("#submit_reimbursement").after('<span class="errors">Please complete at least one field</span>');
				return false;
			}else if (receipt_errors==true){
				$("#submit_reimbursement").after('<span class="errors">Invalid Receipt</span>');
				return false;				
			}else{
				return true;
			}

		});
	});

	$(function(){
		$("#submit_dinner").click(function(){

			//Assume no errors, erase all errors, red flags, etc.
			var errors = false;
			$(".errors").remove();
			$("#date_dinner").attr("class", "default");	
			var brothers=0;

			//Error 1:  Date Field is empry
			if ($("#date_dinner").val() == ""){
				$("#date_dinner").after('<span class="errors">*</span>');
				errors=true;
			}

			//Error 2:  Picture field is empty
			if ($("#pic").val() == ""){
				$("#pic").after('<span class="errors">*</span>');
				errors = true;					
			}

			//Error 3:  There are less than 4 brothers signed up (Note: Still possible to submit)			
			for (var i=0; i<=13; i++){
				if($("#name" + i).val() != "") {
					brothers+=1;
				}
			}
			if (brothers<4){
				alert("Warning: You have less than four brothers at this dinner");
			}


			//If there are errors, don't allow submission of form
			if (errors == true){			
				$("#submit_dinner").after('<span class="errors">Please fix mandatory fields</span>');
				return false;
			}else if (pic_error==1){
				$("#submit_dinner").after('<span class="errors">Invalid Picture</span>');
				return false;				
			}else{
				return true;
			}
		});
	});
</script>
</head>


<body>
	<div id="Header">
	<h1 style="font-size:45px">MIT AEPi Reimbursement</h1>
	</div>

<form  name= "exchequer" id="commentForm" enctype="multipart/form-data" action="send.php" method="POST" >



 	<h4 style="margin-bottom:0">Select Type of Purchase:</h4>
 	<input type="radio" name="type" value="Purchasing" 
 			onclick="document.getElementById('dinner').style.display = 'none';
				document.getElementById('reimbursement').style.display = 'block';
				document.getElementById('aepi_image').style.display = 'none';
				document.getElementById('dinner_sample').style.display = 'none';">
			Purchasing Reimbursement 	<br />
	<input type="radio" name="type" value="Dinner" 
			onclick="document.getElementById('dinner').style.display = 'block';
				document.getElementById('reimbursement').style.display = 'none';
				document.getElementById('aepi_image').style.display = 'none';
				document.getElementById('dinner_sample').style.display = 'block';">

			Thursday Dinner <br /> 
	
	<img src="aepi.gif" alt="AEPi Logo" id="aepi_image" height="500px">

	<div id="reimbursement">
		<h2>Purchasing Reimbursement</h2>
		<?php
		  print 'Payment to: <b>' . $_SERVER['SSL_CLIENT_S_DN_CN'] . '</b>.<br />';
		 ?>

		<p id="file_description" ><i>Maximum File Size:  2MB <br />
			Accepted File Types: JPEG, JPG, PNG, PDF</i></p>

		<table>
		<tr>
	  		<th></th>
	  		<th>Date of Purchase</th>
	  		<th>Purchase Description</th> 
	  		<th>Amount</th>
	  		<th>Receipt Upload
	  		</th>
		</tr>
		<tr id="ReceiptRow1">
			<td>Receipt #1</td>
			<td> <input name="date_reimbursement1" id= "date_reimbursement1" class="default" type="date" size="5" />	</td>
			<td><textarea name="description1" id = "description1" class="default" cols="50" rows="1" ></textarea></td>
			<td>$<textarea name="cost1" id="cost1" class="default" cols="10" rows="1" ></textarea></td>
			<td><input  name="receipt1" id="receipt1" class="default" type="file" /></td>
		</tr>
		<tr id="ReceiptRow2">
			<td>Receipt #2</td>
			<td> <input name="date_reimbursement2" id= "date_reimbursement2" class="default" type="date" size="5" />	</td>
			<td><textarea name="description2" id = "description2" class="default" cols="50" rows="1"></textarea></td>
			<td>$<textarea name="cost2" id="cost2" class="default" cols="10" rows="1"></textarea></td>
			<td><input  name="receipt2" id="receipt2" class="default" type="file"/></td>
		</tr>
		<tr id="ReceiptRow3">
			<td>Receipt #3</td>
			<td> <input name="date_reimbursement3" id= "date_reimbursement3" class="default" type="date" size="5" />	</td>
			<td><textarea name="description3" id = "description3" class="default" cols="50" rows="1"></textarea></td>
			<td>$<textarea name="cost3" id="cost3" class="default" cols="10" rows="1"></textarea></td>
			<td><input  name="receipt3" id="receipt3" class="default" type="file"/></td>
		</tr>
		<tr id="ReceiptRow4">
			<td>Receipt #4</td>
			<td> <input name="date_reimbursement4" id= "date_reimbursement4" class="default" type="date" size="5" />	</td>
			<td><textarea name="description4" id = "description4" class="default" cols="50" rows="1"></textarea></td>
			<td>$<textarea name="cost4" id="cost4" class="default" cols="10" rows="1"></textarea></td>
			<td><input  name="receipt4" id="receipt4" class="default" type="file"/></td>
		</tr>
		<tr id="ReceiptRow5">
			<td>Receipt #5</td>
			<td> <input name="date_reimbursement5" id= "date_reimbursement5" class="default" type="date" size="5" />	</td>
			<td><textarea name="description5" id = "description5" class="default" cols="50" rows="1"></textarea></td>
			<td>$<textarea name="cost5" id="cost5" class="default" cols="10" rows="1"></textarea></td>
			<td><input  name="receipt5" id="receipt5" class="default" type="file"/></td>
		</tr>

		</table>

		<script >
			var receipt_error = [0,0,0,0,0,0]; 
			for (var i=1; i<=5; i++){	
				$('#receipt'+i).bind('change', function() {
					receipt_error[i]=0;
					$(".receipt_errors").remove();
		  		//this.files[0].size gets the size of your file.
		  			if (this.files[0].size/1024/1024 > 2){ //max 2 MB
		  				$(this).after('<span class="receipt_errors">This file is too big: maximum size is 2MB</span>');
		  				receipt_error[i]=1;
		  			}
		  			else if ((this.files[0].type!= "image/jpeg") && (this.files[0].type!= "image/jpg") && 
		  				(this.files[0].type!= "image/png") && (this.files[0].type!= "application/pdf")){
						$(this).after('<span class="receipt_errors">Must be JPEG, JPG, PNG, or PDF</span>');
		  				receipt_error[i]=1;
		  			}
				});
		  			
			}
		</script>
		<div id="total"><b>Total Reimbursement:</b></div>
		<script type="text/javascript">
		$("#cost1").bind("input propertychange", updateTotal);
		$("#cost2").bind("input propertychange", updateTotal);
		$("#cost3").bind("input propertychange", updateTotal);
		$("#cost4").bind("input propertychange", updateTotal);
		$("#cost5").bind("input propertychange", updateTotal);
		function updateTotal() {
			var total = 0;
	 		var cost1 = parseFloat($("#cost1").val());
	 		if (cost1 >= 0){total+=cost1;};
	 		var cost2 = parseFloat($("#cost2").val());
	 		if (cost2 >= 0){total+=cost2;};
	 		var cost3 = parseFloat($("#cost3").val());
	 		if (cost3 >= 0){total+=cost3;};
	 		var cost4 = parseFloat($("#cost4").val());
	 		if (cost4 >= 0){total+=cost4;};	
	 		var cost5 = parseFloat($("#cost5").val());
	 		if (cost5 >= 0){total+=cost5;};
	 		var round_total = total.toFixed(2);
	 		$("#total").html("<b>Total Reimbursement: $"+round_total+"</b>");
		}


		</script>
		<input type="submit" id="submit_reimbursement" name="submit_reimbursement" value="Submit" />

	</div>


	<img src="aepi.gif" alt="Sample Dinner Picture" id="dinner_sample" height="50%">

	<div id="dinner">
		<h2 style="margin-bottom:0">Thursday Night Dinner Reimbursement</h2>

		<p class="dinner_p" style="font-size:12px; margin-top:0.1; margin-bottom:.1"> Each brother listed below will receive $10/person </p>
		<?php
		  print '<p style="font-size:12px; margin:0;">Form Filled Out By: <b>' . $_SERVER['SSL_CLIENT_S_DN_CN'] . '</b>.</p>'; ?>

		<p class="dinner_p"><b>Step 1:</b> Date of Dinner: <input id="date_dinner" name="date_dinner" class = "default" type="date" size="5" />	<br /> </p>
		<p class="dinner_p" style="margin-bottom:0"><b>Step 2:</b> List people that attended (including yourself):</p>
			<input  id="name0" name="person0" type="text" list="brothers" size="20" placeholder = "Enter Your Name Here" /> <input  id="name7" name="person7" type="text" list="brothers" size="20" /><br />
			<input  id="name1" name="person1" type="text" list="brothers" size="20" placeholder = "e.g. Noam Buckman"/> <input  id="name8" name="person8" type="text" list="brothers" size="20" /><br />
			<input  id="name2" name="person2" type="text" list="brothers" size="20" /> <input  id="name9" name="person9" type="text" list="brothers" size="20" /><br />
			<input  id="name3" name="person3" type="text" list="brothers" size="20" /> <input  id="name10" name="person10" type="text" list="brothers" size="20" /><br />
			<input  id="name4" name="person4" type="text" list="brothers" size="20" /> <input  id="name11" name="person11" type="text" list="brothers" size="20" /><br />
			<input  id="name5" name="person5" type="text" list="brothers" size="20" /> <input  id="name12" name="person12" type="text" list="brothers" size="20" /><br />
			<input  id="name6" name="person6" type="text" list="brothers" size="20" /> <input  id="name13" name="person13" type="text" list="brothers" size="20" /><br />
			<datalist id="brothers">
			  <option value="Aaron">
			  <option value="Adam">  
			  <option value="Alex">
			  <option value="Ariel">
			  <option value="Azaria">  
			  <option value="Ben">
  			  <option value="Bertie">
			  <option value="Blake">
			  <option value="Daniel">
			  <option value="David">  
			  <option value="Drew">
			  <option value="Elisha">
			  <option value="Evan">
			  <option value="George">
			  <option value="Isaac">
			  <option value="Jason">  
			  <option value="Jeffrey">
			  <option value="Jesse">
			  <option value="Jonathan">
			  <option value="Jonathan">  
			  <option value="Jonathan">
			  <option value="Jordan">
			  <option value="Lane">
			  <option value="Marcus">
			  <option value="Matt">  
			  <option value="Maxwell">
			  <option value="Micha">
			  <option value="Noam">
			  <option value="Steven">  
			  <option value="Ziv">
			</datalist>

		<p class="dinner_p" style="margin-bottom:0"><b>Step 3:</b> Upload Picture of Dinner: <input type="file" name="pic" id="pic" /> </p>
		<p id="file_description" style="margin:0"><i>Maximum File Size:  2MB <br />
			Accepted File Types: JPEG, JPG, PNG, PDF</i></p>

		<script >

			var pic_error;
			$('#pic').bind('change', function() {
					pic_error=0;
	  		//this.files[0].size gets the size of your file.
	  			if (this.files[0].size/1024/1024 > 2){ //max 2 mb
	  				alert("This file is too big, maximum size is 2MB");
	  				pic_error=1
	  			}
	  			if ((this.files[0].type!= "image/jpeg") && (this.files[0].type!= "image/jpg") && 
	  				(this.files[0].type!= "image/png") && (this.files[0].type!= "application/pdf")){
	  				alert("This is not a legal type, acceptable files: jpeg, jpg, png, pdf");
	  				pic_error=1;
	  			}
			});
		
		</script>

		<p class="dinner_p"><b>Step 4 (Optional):</b> <input type="checkbox" name="uno" value="uno"> Check here if you are being reimbursed for everyone<br /></p>
		<p class="dinner_p"><input type="submit" id="submit_dinner" name="submit_dinner" value="Submit" />	</p>
	</div>


	</form>

</body>
