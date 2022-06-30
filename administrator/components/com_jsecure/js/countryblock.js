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
		document.getElementById("redirectOptions").style.display = "none";
		document.getElementById("countryblockfrntpath").style.display = "none";
	 } else {
		 document.getElementById("countrylist").style.display = "";
		 document.getElementById("countries").style.display = "";
		 document.getElementById("checkall").style.display = "";
		 document.getElementById("countryblockfrontend").style.display = "";
		 document.getElementById("redirectOptions").style.display = "";
		document.getElementById("countryblockfrntpath").style.display = "";
    }
	
}
}

 
 

function pathfrontend(optioncheck){
	var j = jQuery.noConflict();
 j(document).ready(function()
 { 
  j('#countryfrnt_options1').css({'opacity':'0','outline':'0'});
  j('#countryfrnt_options0').css({'opacity':'0','outline':'0'});

  if (j('#countryfrnt_options0').attr('checked'))
  {
      j('#countryblockfrntpath').hide();
   j("label[for='"+j('#countryfrnt_options0').attr('id')+"']").attr('class', 'btn active btn-danger');
   j("label[for='"+j('#countryfrnt_options1').attr('id')+"']").attr('class', 'btn active');
  }
  
  if (j('#countryfrnt_options1').attr('checked'))
  {
      j('#countryblockfrntpath').show();
   j("label[for='"+j('#countryfrnt_options0').attr('id')+"']").attr('class', 'btn active');
   j("label[for='"+j('#countryfrnt_options1').attr('id')+"']").attr('class', 'btn active btn-success');
  }  
  
  j('#countryfrnt_options1').bind('click', function()
  {
   j('#countryblockfrntpath').show();
   j("label[for='"+j('#countryfrnt_options1').attr('id')+"']").attr('class', 'btn active btn-success');
   j("label[for='"+j('#countryfrnt_options0').attr('id')+"']").attr('class', 'btn active');
   
     });
 
  j('#countryfrnt_options0').bind('click', function()
  {
   j('#countryblockfrntpath').hide();
   j("label[for='"+j('#countryfrnt_options0').attr('id')+"']").attr('class', 'btn active btn-danger');
   j("label[for='"+j('#countryfrnt_options1').attr('id')+"']").attr('class', 'btn active');
     });
 
   });
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

if(document.getElementById('countryfrnt_options0').checked){
    document.getElementById("countryblockfrntpath").style.display = "none";
}
}

