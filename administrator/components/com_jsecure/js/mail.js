function checkMailStatus(sendMail){
if(sendMail.value != null)
{
	if(sendMail.value == true){
		document.getElementById("sendMailDetails").style.display = "";
		document.getElementById("emailid").style.display = "";
		document.getElementById("emailsubject").style.display = "";
	} else {
		document.getElementById("sendMailDetails").style.display  = "none";
		document.getElementById("emailid").style.display          = "none";
		document.getElementById("emailsubject").style.display     = "none";
		
	}
	}
}
Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}	
	if(pressbutton=="cancel"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return false;
	}
	if(submitForm.sendemail != undefined)
	{
	if(submitForm.sendemail.value == 1){
		if(!checkEMail(submitForm.emailid.value)){
			alert("Please enter proper Email ID");
			submitForm.emailid.focus();
			return false;
		}
		if(submitForm.emailsubject.value==""){
			alert("Please enter Email Subject");
			submitForm.emailsubject.focus();
			return false;
		}
	}
	}
     
	if(pressbutton=="save"){
		
		submitForm.task.value='saveMail';
		submitForm.submit();
		return true;
	}	


	submitForm.task.value=pressbutton;
	submitForm.submit();
}

function checkEMail(email){
	var reg = /^[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,4}(?:[,;][A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,4})*$/i;
	if(reg.test(email) == false) {
		return false;
	} else {
		return true;
	}
}

//*/
function isNumeric(val)
{
	val.value=val.value.replace(/[^0-9*]/g, '');
	if (val.value.indexOf('*') != '-1')
		val.value = '*';
}

var j = jQuery.noConflict();
	j(document).ready(function()
	{	
		j('#sendemail1').css({'opacity':'0','outline':'0'});
        j('#sendemail0').css({'opacity':'0','outline':'0'});
		if (j('#sendemail0').attr('checked'))
		{
	   		j('#sendemaildetails').hide();
			j('#emailid').hide();
			j('#emailsubject').hide();
			 j("label[for='"+j('#sendemail0').attr('id')+"']").attr('class', 'btn active btn-danger');
             j("label[for='"+j('#sendemail1').attr('id')+"']").attr('class', 'btn active');
		}
		if (j('#sendemail1').attr('checked'))
		{
	   		j('#sendemaildetails').show();
			j('#emailid').show();
			j('#emailsubject').show();
			 j("label[for='"+j('#sendemail0').attr('id')+"']").attr('class', 'btn active');
             j("label[for='"+j('#sendemail1').attr('id')+"']").attr('class', 'btn active btn-success');
		}
		
		j('#sendemail1').bind('click', function()
		{
			j('#sendemaildetails').show();
			j('#emailid').show();
			j('#emailsubject').show();
			 j("label[for='"+j('#sendemail1').attr('id')+"']").attr('class', 'btn active btn-success');
             j("label[for='"+j('#sendemail0').attr('id')+"']").attr('class', 'btn active');

   		});
	
		j('#sendemail0').bind('click', function()
		{
			j('#sendemaildetails').hide();
			j('#emailid').hide();
			j('#emailsubject').hide();
			j("label[for='"+j('#sendemail0').attr('id')+"']").attr('class', 'btn active btn-danger');
            j("label[for='"+j('#sendemail1').attr('id')+"']").attr('class', 'btn active');
   		});
	
   });