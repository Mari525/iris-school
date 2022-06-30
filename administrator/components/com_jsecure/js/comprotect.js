Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}
	if(pressbutton=="save"){
		submitForm.task.value='saveComprotect';
		submitForm.submit();
		return true;
	}	
	if(pressbutton=="apply"){
		submitForm.task.value='applyComprotect';
		submitForm.submit();
		return true;
	}	
	
	submitForm.task.value=pressbutton;
	submitForm.submit();
}

function test()
{
	alert("test");
	return false;
}
