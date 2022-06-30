Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}	 
	if(pressbutton=="save"){
		
		submitForm.task.value='saveLog';
		submitForm.submit();
		return true;
	}	
	submitForm.task.value=pressbutton;
	submitForm.submit();
}
//*/
function isNumeric(val)
{
	val.value=val.value.replace(/[^0-9*]/g, '');
	if (val.value.indexOf('*') != '-1')
		val.value = '*';
}