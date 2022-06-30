Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if (pressbutton == 'cancel') {
    submitForm.task.value = pressbutton;
    submitForm.submit();
    return true;                               
    }

	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}
	
}

function getwhoisinfo(){

	var domain_value = document.getElementById('domain_lookup').value;
		
	if(domain_value == ''){
	alert('Please Enter Website Domain');
	return false;
	}

	var submitForm = document.adminForm;
	submitForm.task.value="whois";
	submitForm.submit();
	return true;
}



