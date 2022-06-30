Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}	
	
	if(submitForm.master_password.value !=undefined)
	{
	if(submitForm.master_password.value != "" && submitForm.ret_master_password.value == ""){
		alert("Please enter Verify Master Password");
		submitForm.ret_master_password.focus();
		return false;
	}
	if((submitForm.master_password.value != "") && (submitForm.ret_master_password.value != submitForm.master_password.value)){
		alert("Please enter Verify Master Password Same as of Master Password");
		submitForm.ret_master_password.focus();
		return false;
	}
	if(submitForm.master_password.value == "" && submitForm.ret_master_password.value != ""){
		alert("Please enter Master Password first");
		submitForm.ret_master_password.value="";
		submitForm.master_password.focus();
		return false;
	}
	}
	
	if(!alphanumeric(submitForm.master_password.value)){
		submitForm.master_password.value="";
		alert("Master Password should not have special characters. Please enter Alpha-Numeric Key");
		submitForm.master_password.focus();
		return false;
	}

	
	if(pressbutton=="save"){
		submitForm.task.value='saveMasterpwd';
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
function alphanumeric(keyValue){
	
	var numaric = keyValue;
	for(var j=0; j<numaric.length; j++){
		  var alphaa = numaric.charAt(j);
		  var hh = alphaa.charCodeAt(0);
		  if(!((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123))){
		  	return false;
		  }
	}
	return true;
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
		j('#MasterPasswordenable1').css({'opacity':'0','outline':'0'});
        j('#MasterPasswordenable0').css({'opacity':'0','outline':'0'});
		j('#switch_button1').css({'opacity':'0','outline':'0'});
        j('#switch_button0').css({'opacity':'0','outline':'0'});
        j('#switch_button1').bind('click', function(event) {
			j("#adminForm :input[id^='include_']").each(function() {
			 if (this.value == 1)
			 {
                this.checked = true;
				j("label[for='"+j(this).attr('id')+"']").attr('class', 'btn active btn-success');
			 } else {
			       this.checked = false;
				 j("label[for='"+j(this).attr('id')+"']").attr('class', 'btn');
			 }
			  
		   });
			
		  });
        j('#switch_button0').bind('click', function(event) {
			j("#adminForm :input[id^='include_']").each(function() {
			  if (this.value == 0)
			 {
                this.checked = true;
				j("label[for='"+j(this).attr('id')+"']").attr('class', 'btn active btn-danger');
			 } else {
			       this.checked = false;
				 j("label[for='"+j(this).attr('id')+"']").attr('class', 'btn');
			 } 
			
		   });
			
		  });

		if (j('#MasterPasswordenable0').attr('checked'))
		{
			j('#master_password').hide();
		    j('#verify_master_password').hide();
		    j('#include_basic_confg').hide();
			j('#include_email_scan').hide();
		    j('#include_image_secure').hide();
		    j('#include_user_key').hide();
		    j('#include_country_block').hide();
		    j('#include_change_db_prefix').hide();
		    j('#include_whois').hide();
		    j('#include_adminpwdpro').hide();
		    j('#include_mail').hide();
		    j('#include_ip').hide();
		    j('#include_mastermail').hide();
		    j('#include_log').hide();
		    j('#include_showlogs').hide();
		    j('#include_directorylisting').hide();
			j('#include_component_protection').hide();
			j('#include_autobanip').hide();
			j('#include_graph').hide();
		    j('#include_adminid').hide();
		    j('#include_logincontrol').hide();
		    j('#include_metatags').hide();
		    j('#include_purgesessions').hide();
		    j('#quick_select').hide();
			j('#quick_title').hide();
			j("label[for='"+j('#MasterPasswordenable0').attr('id')+"']").attr('class', 'btn active btn-danger');
            j("label[for='"+j('#MasterPasswordenable1').attr('id')+"']").attr('class', 'btn active');
		}
		if (j('#MasterPasswordenable1').attr('checked'))
		{
			j('#master_password').show();
		    j('#verify_master_password').show();
		    j('#include_basic_confg').show();
			j('#include_email_scan').show();
		    j('#include_image_secure').show();
			j('#include_user_key').show();
			j('#include_country_block').show();
			j('#include_change_db_prefix').show();
			j('#include_whois').show();
		    j('#include_adminpwdpro').show();
		    j('#include_mail').show();
		    j('#include_ip').show();
		    j('#include_mastermail').show();
		    j('#include_log').show();
		    j('#include_showlogs').show();
		    j('#include_directorylisting').show();
			j('#include_component_protection').show();
			j('#include_autobanip').show();
		    j('#include_adminid').show();
		    j('#include_logincontrol').show();
		    j('#include_metatags').show();
		    j('#include_purgesessions').show();
		    j('#quick_select').show();
			j('#quick_title').show();
			 j("label[for='"+j('#MasterPasswordenable0').attr('id')+"']").attr('class', 'btn active');
             j("label[for='"+j('#MasterPasswordenable1').attr('id')+"']").attr('class', 'btn active btn-success');
		}
		
		j('#MasterPasswordenable1').bind('click', function()
		{
			j('#master_password').show();
		    j('#verify_master_password').show();
		    j('#include_basic_confg').show();
			j('#include_email_scan').show();
		    j('#include_image_secure').show();
			j('#include_user_key').show();
			j('#include_country_block').show();
			j('#include_change_db_prefix').show();
			j('#include_whois').show();
		    j('#include_adminpwdpro').show();
		    j('#include_mail').show();
		    j('#include_ip').show();
		    j('#include_mastermail').show();
		    j('#include_log').show();
		    j('#include_showlogs').show();
		    j('#include_directorylisting').show();
			j('#include_component_protection').show();
			j('#include_autobanip').show();
			j('#include_graph').show();
		    j('#include_adminid').show();
		    j('#include_logincontrol').show();
		    j('#include_metatags').show();
		    j('#include_purgesessions').show();
		    j('#quick_select').show();
			j('#quick_title').show();
			j("label[for='"+j('#MasterPasswordenable1').attr('id')+"']").attr('class', 'btn active btn-success');
            j("label[for='"+j('#MasterPasswordenable0').attr('id')+"']").attr('class', 'btn active');

   		});
	
		j('#MasterPasswordenable0').bind('click', function()
		{
			j('#master_password').hide();
		    j('#verify_master_password').hide();
		    j('#include_basic_confg').hide();
			j('#include_email_scan').hide();
		    j('#include_image_secure').hide();
			j('#include_user_key').hide();
			j('#include_country_block').hide();
			j('#include_change_db_prefix').hide();
			j('#include_whois').hide();
		    j('#include_adminpwdpro').hide();
		    j('#include_mail').hide();
		    j('#include_ip').hide();
		    j('#include_mastermail').hide();
		    j('#include_log').hide();
		    j('#include_showlogs').hide();
		    j('#include_directorylisting').hide();
			j('#include_component_protection').hide();
			j('#include_autobanip').hide();
			j('#include_graph').hide();
		    j('#include_adminid').hide();
		    j('#include_logincontrol').hide();
		    j('#include_metatags').hide();
		    j('#include_purgesessions').hide();
		    j('#quick_select').hide();
			j('#quick_title').hide();
			 j("label[for='"+j('#MasterPasswordenable0').attr('id')+"']").attr('class', 'btn active btn-danger');
             j("label[for='"+j('#MasterPasswordenable1').attr('id')+"']").attr('class', 'btn active');
   		});
	
   });