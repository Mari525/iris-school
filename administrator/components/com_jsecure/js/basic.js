function hideCustomPath(optionsValue){
	if(optionsValue.value == "1"){
		document.getElementById("custom_path").style.display = "";
	} else {
		document.getElementById("custom_path").style.display = "none";
	}
}

function hidefield()
{ var captchapublish1=document.getElementById("captchapublish1").checked;
  var captchapublish0=document.getElementById("captchapublish0").checked;
  if(captchapublish1)
     {   document.getElementById("captchakey1").style.display = "";
          document.getElementById("captchakey").style.display = "";	
         //document.getElementById("captchakey2").style.display = "";		  
          document.getElementById("captchadatasitekey1").style.display = ""; 
          document.getElementById("captchadatasitekey").style.display = "";	
          document.getElementById("USEFULLINKS").style.display = "";	
          //document.getElementById("captchadatasitekey2").style.display = "";			  
	 }
  if(captchapublish0)
     {  document.getElementById("captchakey1").style.display = "none";
	   //document.getElementById("captchakey2").style.display = "none";
          document.getElementById("captchakey").style.display = "none";	 
         document.getElementById("captchadatasitekey1").style.display = "none"; 
         document.getElementById("USEFULLINKS").style.display = "none"; 
		 //document.getElementById("captchadatasitekey2").style.display = "none"; 
          document.getElementById("captchadatasitekey").style.display = "none";	 
	 }
}

Joomla.submitbutton = function(pressbutton){


	
	var submitForm = document.adminForm;
	
		
		
	if (pressbutton == 'cancel') {
	
			
		 submitForm.task.value = pressbutton;
		 submitForm.submit();
		 return true;								// bypass form validation on cancel
	}
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;

	}
	
	if(!alphanumeric(submitForm.key.value)){
		submitForm.key.value="";
		alert("Secret Key should not have special characters. Please enter Alpha-Numeric Key");
		submitForm.key.focus();
		return false;
	}
	
	else if(alphanumeric(submitForm.key.value) == 5){
		alert("Secret Key cannot be less than 5 or more than 20 characters");
		submitForm.key.focus();
		return false;
	}
	
	if(pressbutton=="saveBasic"){
	
	if(submitForm.captchakey.value.trim()== "" && document.getElementById("captchapublish1").checked)
	  { alert("captcha key field cant be empty");
	    return false;
	  }
	if(submitForm.captchadatasitekey.value.trim()== "" && document.getElementById("captchapublish1").checked)
	  { alert("captcha data site key field cant be empty");
	    return false;
	  }
	
		
		submitForm.task.value='saveBasic';
		submitForm.submit();
		return true;
	}
	
	if(pressbutton=="applyBasic"){
	
	if(submitForm.captchakey.value.trim()== "" && document.getElementById("captchapublish1").checked)
	  { alert("captcha key field cant be empty");
	    return false;
	  }
	if(submitForm.captchadatasitekey.value.trim()== "" && document.getElementById("captchapublish1").checked)
	  { alert("captcha data site key field cant be empty");
	    return false;
	  }
	  
	  submitForm.task.value='applyBasic';
		submitForm.submit();
		return true;
	
	
	}

	  
	submitForm.task.value=pressbutton;
	submitForm.submit();
}

function alphanumeric(keyValue){
	
	var numaric = keyValue;
	for(var j=0; j<numaric.length; j++){
		  var alphaa = numaric.charAt(j);
		  var hh = alphaa.charCodeAt(0);
		  if(!((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123))){
		  	return false;
		  }
		  else if(numaric.length < 5 || numaric.length > 20){
			return 5;
		  }
	}
	
	
	
	return true;
}
function isNumeric(val)
{
	val.value=val.value.replace(/[^0-9*]/g, '');
	if (val.value.indexOf('*') != '-1')
		val.value = '*';
}

var j = jQuery.noConflict();
 j(document).ready(function()
 { 
  j('#options1').css({'opacity':'0','outline':'0'});
  j('#options0').css({'opacity':'0','outline':'0'});

  if (j('#options0').attr('checked'))
  {
      j('#custom_path').hide();
   j("label[for='"+j('#options0').attr('id')+"']").attr('class', 'btn active btn-danger');
   j("label[for='"+j('#options1').attr('id')+"']").attr('class', 'btn active');
  }
  
  if (j('#options1').attr('checked'))
  {
      j('#custom_path').show();
   j("label[for='"+j('#options0').attr('id')+"']").attr('class', 'btn active');
   j("label[for='"+j('#options1').attr('id')+"']").attr('class', 'btn active btn-success');
  }  
  
  j('#options1').bind('click', function()
  {
   j('#custom_path').show();
   j("label[for='"+j('#options1').attr('id')+"']").attr('class', 'btn active btn-success');
   j("label[for='"+j('#options0').attr('id')+"']").attr('class', 'btn active');
   
     });
 
  j('#options0').bind('click', function()
  {
   j('#custom_path').hide();
   j("label[for='"+j('#options0').attr('id')+"']").attr('class', 'btn active btn-danger');
   j("label[for='"+j('#options1').attr('id')+"']").attr('class', 'btn active');
     });
 
   });
   
   