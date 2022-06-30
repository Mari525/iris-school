/* Check controller.php for Autoban Tasks(save,apply,cancel) */


function spamipLising(optValue){
if(optValue.value != null && optValue.value != undefined  )
{


	if(optValue.value == "0"){
		document.getElementById("SPAMIPLISTING").style.display = "none";
		document.getElementById("SPAMIPHONEYPOTKEY").style.display = "none";
		document.getElementById("SPAMIPLISTING").style.display = "none";
		document.getElementById("ALLOWEDTHREATRATING").style.display = "none";
		document.getElementById("USEFULLINKS").style.display = "none";
		
		
	} else {
		document.getElementById("SPAMIPLISTING").style.display = "";
		document.getElementById("SPAMIPHONEYPOTKEY").style.display = "";
		document.getElementById("SPAMIPLISTING").style.display = "";
		document.getElementById("ALLOWEDTHREATRATING").style.display = "";
		document.getElementById("USEFULLINKS").style.display = "";
	}
}
}

Joomla.submitbutton = function(pressbutton){

	var submitForm = document.adminForm;
	
	
	
	
	if (pressbutton == 'cancel') {
	
			
		 submitForm.task.value = pressbutton;
		 submitForm.submit();
		 return true;								// bypass form validation on cancel
	}
	
	if (pressbutton == 'save') {
	
			var result = validateNum();				// bypass form validation on cancel
	
			if(result){
			 submitForm.task.value = pressbutton;
		 submitForm.submit();
		 
		 return true;
			
			}
	
	}
	
	if (pressbutton == 'apply') {
	
			var saveresult = validateNum();			// bypass form validation on cancel
	
			if(saveresult){
			submitForm.task.value = pressbutton;
		 submitForm.submit();
		 
		 return true;
			
			}	
	
	}
	
	

}

function validateNum() {
    var x = document.getElementById("allowedthreatrating").value;
    
	var optvalue = document.getElementById('spamip').value;
	

		
	if(optvalue == '1'){
	
	if (isNaN(x) || x < 0 || x > 255) {
        alert("number out of range");
        return false;
    }
	else
	  {   
	    return true;
	  }
	  
	}
	else{
	
	return true;
	
	}
}

