Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	
	if(pressbutton=="addUserkey"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;

	}
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}
	if(pressbutton=="save"){
		submitForm.task.value='saveUserkey';
		submitForm.submit();
		return true;
	}	
	if(pressbutton=="apply"){
		submitForm.task.value='applyUserkey';
		submitForm.submit();
		return true;
	}	
	/* custom js code for enabling form validation */
	// if (document.formvalidator.isValid(submitForm)) {
        // submitForm.task.value = pressbutton;
         // submitForm.submit();
       // }
       // else {
         // alert('Fields highlighted in red are compulsory or unacceptable!');
       // }
	submitForm.task.value=pressbutton;
	submitForm.submit();
}

