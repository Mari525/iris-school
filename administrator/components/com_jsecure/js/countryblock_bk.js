Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;

	if (pressbutton == 'cancelcountrylog') {				
		 submitForm.task.value = pressbutton;
		 submitForm.submit();
		 return true;								
	}
	
	if (pressbutton == 'removecountrylog') {
	
		 submitForm.task.value = pressbutton;
		
		 submitForm.submit();
		 return true;								
	}
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}
		
	
	if(pressbutton=="saveCountryblock"){

		submitForm.task.value='saveCountryblock';
		submitForm.submit();
		return true;
		
	}
	
	if(pressbutton=="applyCountryblock"){

	 
	  submitForm.task.value='applyCountryblock';
		submitForm.submit();
		return true;
}


if(submitForm.task.value=="publish"){

 submitForm.task.value='publish';
		submitForm.submit();
		return true;
}


if(submitForm.task.value=="unpublish"){

 submitForm.task.value='unpublish';
		submitForm.submit();
		return true;
}


/* Search is not working so we add temporary condition */
if(submitForm.task.value == 'countrylog'){
submitForm.task.value == 'countrylog';
}
else{

submitForm.task.value = pressbutton;

}

submitForm.submit();

}


function countryblock(optValue){

if(optValue != null && optValue != undefined  )
{

    if(optValue == "0" || optValue.value == "0"){
        document.getElementById("countrylist").style.display = "none";
		document.getElementById("countries").style.display = "none";
		document.getElementById("checkall").style.display = "none";
		document.getElementById("countryblockfrontend").style.display = "none";
        
    } else {
		 document.getElementById("countrylist").style.display = "";
		 document.getElementById("countries").style.display = "";
		 document.getElementById("checkall").style.display = "";
		 document.getElementById("countryblockfrontend").style.display = "";
    }
}
}

function activate_tab(){

 var submitForm = document.adminForm;
 document.getElementById("task").value ="countrylog";
 submitForm.submit();
}

function switch_tab(){

 var submitForm = document.adminForm;
 document.getElementById("task").value ="countryblock";
 submitForm.submit();
}


function init(){
if(document.getElementById('publishcountryblock1').checked){
var enableopt = document.getElementById('publishcountryblock1').value;
}
else{
var enableopt = document.getElementById('publishcountryblock0').value;
}
countryblock(enableopt);
if(document.getElementById('publishcountryblockfront1').checked){
var opt = document.getElementById('publishcountryblockfront1').value;
}
else{
var opt = document.getElementById('publishcountryblockfront0').value;
}
countryblockfront(opt);

}








