Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}	
	if(pressbutton=="save"){
		
		submitForm.task.value='saveMetatags';
		submitForm.submit();
		return true;
	}	


	submitForm.task.value=pressbutton;
	submitForm.submit();
}
function hideAdminMetaTags(tagval)
{  
    if(tagval.value == "1"){
    	document.getElementById("tag_generator").style.display = "";
	    document.getElementById("tag_keywords").style.display = "";
		document.getElementById("tag_description").style.display = "";
		document.getElementById("tag_rights").style.display = "";
	} else {
	    document.getElementById("tag_generator").style.display = "none";
	    document.getElementById("tag_keywords").style.display = "none";
		document.getElementById("tag_description").style.display = "none";
		document.getElementById("tag_rights").style.display = "none";
	}
}
function checkEMail(email){
	var reg = /^[A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,4}(?:[,;][A-Z0-9\._%-]+@[A-Z0-9\.-]+\.[A-Z]{2,4})*$/i;
	if(reg.test(email) == false) {
		return false;
	} else {
		return true;
	}
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
		j('#metatagcontrol1').css({'opacity':'0','outline':'0'});
        j('#metatagcontrol0').css({'opacity':'0','outline':'0'});
		if (j('#metatagcontrol0').attr('checked'))
		{
	   		j('#tag_generator').hide();
			j('#tag_keywords').hide();
			j('#tag_description').hide();
			j('#tag_rights').hide();
			j("label[for='"+j('#metatagcontrol0').attr('id')+"']").attr('class', 'btn active btn-danger');
            j("label[for='"+j('#metatagcontrol1').attr('id')+"']").attr('class', 'btn active');
		}
		if (j('#metatagcontrol1').attr('checked'))
		{
	   		j('#tag_generator').show();
			j('#tag_keywords').show();
			j('#tag_description').show();
			j('#tag_rights').show();
			j("label[for='"+j('#metatagcontrol0').attr('id')+"']").attr('class', 'btn active');
            j("label[for='"+j('#metatagcontrol1').attr('id')+"']").attr('class', 'btn active btn-success');
		}
		
		j('#metatagcontrol1').bind('click', function()
		{
			j('#tag_generator').show();
			j('#tag_keywords').show();
			j('#tag_description').show();
			j('#tag_rights').show();
			j("label[for='"+j('#metatagcontrol1').attr('id')+"']").attr('class', 'btn active btn-success');
            j("label[for='"+j('#metatagcontrol0').attr('id')+"']").attr('class', 'btn active');

   		});
	
		j('#metatagcontrol0').bind('click', function()
		{
			j('#tag_generator').hide();
			j('#tag_keywords').hide();
			j('#tag_description').hide();
			j('#tag_rights').hide();
			j("label[for='"+j('#metatagcontrol0').attr('id')+"']").attr('class', 'btn active btn-danger');
            j("label[for='"+j('#metatagcontrol1').attr('id')+"']").attr('class', 'btn active');
   		});
	
   });