Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if(pressbutton=="cancel"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}	
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}	
	
	
	
	 if(submitForm.adminpasswordpro != undefined)
	{
	 var j = jQuery.noConflict();
		 if (j('#adminpasswordpro1').attr('checked'))
		{
  if(submitForm.passwordproconfig.value == 0 && pressbutton !="cancel")
  {
      if(submitForm.admin_username.value==""){
			alert("Please Enter Administrator Username");
			submitForm.admin_username.focus();
			return false;
		}
		if(submitForm.admin_password.value==""){
			alert("Please Enter Administrator Passowrd");
			submitForm.admin_password.focus();
			return false;
		}
		if(submitForm.re_admin_password.value==""){
			alert("Please Enter Varify Administrator Passowrd");
			submitForm.admin_password.focus();
			return false;
		}
	  if(submitForm.admin_password.value != submitForm.re_admin_password.value){
		alert("Please enter Verify Administrator Password Same as of Administrator Password");
		submitForm.re_admin_password.focus();
		return false;
	}
  
  }
  }
	}
	if(pressbutton=="save"){
		submitForm.task.value='saveAdminpwdpro';
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
var j = jQuery.noConflict();
	j(document).ready(function()
	{	
		j('#adminpasswordpro1').css({'opacity':'0','outline':'0'});
        j('#adminpasswordpro0').css({'opacity':'0','outline':'0'});
		if (j('#adminpasswordpro0').attr('checked'))
		{
	   		j('#admin_password').hide();
			j('#verify_admin_password').hide();
			j('#admin_username').hide();
			 j("label[for='"+j('#adminpasswordpro0').attr('id')+"']").attr('class', 'btn active btn-danger');
             j("label[for='"+j('#adminpasswordpro1').attr('id')+"']").attr('class', 'btn active');
		}
		if (j('#adminpasswordpro1').attr('checked'))
		{
	   		j('#admin_password').show();
			j('#verify_admin_password').show();
			j('#admin_username').show();
			 j("label[for='"+j('#adminpasswordpro0').attr('id')+"']").attr('class', 'btn active');
             j("label[for='"+j('#adminpasswordpro1').attr('id')+"']").attr('class', 'btn active btn-success');
		}
		j('#adminpasswordpro1').bind('click', function()
		{
			j('#admin_password').show();
			j('#verify_admin_password').show();
			j('#admin_username').show();
			j("label[for='"+j('#adminpasswordpro1').attr('id')+"']").attr('class', 'btn active btn-success');
            j("label[for='"+j('#adminpasswordpro0').attr('id')+"']").attr('class', 'btn active');

   		});
	
		j('#adminpasswordpro0').bind('click', function()
		{
			j('#admin_password').hide();
			j('#verify_admin_password').hide();
			j('#admin_username').hide();
			 j("label[for='"+j('#adminpasswordpro0').attr('id')+"']").attr('class', 'btn active btn-danger');
             j("label[for='"+j('#adminpasswordpro1').attr('id')+"']").attr('class', 'btn active');
   		});
	
   });