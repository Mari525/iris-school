function checkMPMailStatus(sendMail){
if(sendMail.value != undefined)
{
	if(sendMail.value == true){
		document.getElementById("mpemailid").style.display = "";
		document.getElementById("mpemailsubject").style.display = "";
	} else {
		document.getElementById("mpemailid").style.display          = "none";
		document.getElementById("mpemailsubject").style.display     = "none";
		
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
     if(submitForm.mpsendemail != undefined)
	{
	if(submitForm.mpsendemail.value == 1){
		if(submitForm.mpemailsubject.value==""){
			alert("Please enter Email Subject");
			submitForm.mpemailsubject.focus();
			return false;
		}
		if(!checkEMail(submitForm.mpemailid.value)){
			alert("Please enter proper Email ID");
			submitForm.mpemailid.focus();
			return false;
		}
		
	}
	}
	 
	if(pressbutton=="save"){
		
		submitForm.task.value='saveMpmail';
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
		 j('#mpsendemail1').css({'opacity':'0','outline':'0'});
         j('#mpsendemail0').css({'opacity':'0','outline':'0'});
		if (j('#mpsendemail0').attr('checked'))
		{
	   		j('#mpemailsubject').hide();
			j('#mpemailid').hide();
			j("label[for='"+j('#mpsendemail0').attr('id')+"']").attr('class', 'btn active btn-danger');
            j("label[for='"+j('#mpsendemail1').attr('id')+"']").attr('class', 'btn active');

		}
		if (j('#mpsendemail1').attr('checked'))
		{
	   		j('#mpemailsubject').show();
			j('#mpemailid').show();
			j("label[for='"+j('#mpsendemail0').attr('id')+"']").attr('class', 'btn active');
            j("label[for='"+j('#mpsendemail1').attr('id')+"']").attr('class', 'btn active btn-success');

		}
		
		j('#mpsendemail1').bind('click', function()
		{
			j('#mpemailsubject').show();
			j('#mpemailid').show();
			j("label[for='"+j('#mpsendemail1').attr('id')+"']").attr('class', 'btn active btn-success');
            j("label[for='"+j('#mpsendemail0').attr('id')+"']").attr('class', 'btn active');

   		});
	
		j('#mpsendemail0').bind('click', function()
		{
			j('#mpemailsubject').hide();
			j('#mpemailid').hide();
			j("label[for='"+j('#mpsendemail0').attr('id')+"']").attr('class', 'btn active btn-danger');
            j("label[for='"+j('#mpsendemail1').attr('id')+"']").attr('class', 'btn active');
   		});
	
   });