Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
		if (pressbutton == 'closeuserkey') {			
			submitForm.task.value = pressbutton;
			submitForm.submit();
			return true;								// bypass form validation on cancel
		  }
	
	
	/* custom js code for enabling form validation */
	if (document.formvalidator.isValid(submitForm)) {
        submitForm.task.value = pressbutton;
         submitForm.submit();
       }
       else {
         alert('Fields highlighted in red are compulsory or unacceptable!');
       }
	// submitForm.task.value=pressbutton;
	// submitForm.submit();
}

