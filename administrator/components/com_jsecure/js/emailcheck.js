Joomla.submitbutton = function(pressbutton){

	var submitForm = document.adminForm;		
	if (pressbutton == 'cancelspamlog') {				
		 submitForm.task.value = pressbutton;
		 submitForm.submit();
		 return true;								
	}
	
	if (pressbutton == 'cancel') {				
		 submitForm.task.value = pressbutton;
		 submitForm.submit();
		 return true;								
	}
	
	if (pressbutton == 'remove') {				
		 submitForm.task.value = pressbutton;
		 submitForm.submit();
		 return true;								
	}
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}
		
	
	if(pressbutton=="saveEmailcheck"){

       var saveresult = validateNum();

	   if(saveresult){
		submitForm.task.value='saveEmailcheck';
		submitForm.submit();
		return true;
		}
	}
	
	if(pressbutton=="applyEmailcheck"){

	var saveresult = validateNum();
	  
	 if(saveresult){
	  submitForm.task.value='applyEmailcheck';
		submitForm.submit();
		return true;
      }		
	}
	 
	if(saveresult){	
	submitForm.task.value=pressbutton;
	submitForm.submit();
	}
	else{
	return false;
	}
	
	

}

function validateNum() {
    var x = document.getElementById("forumfrequency").value;
	
	
	if(document.getElementById('publishemailcheck1').checked){
	var optvalue = document.getElementById('publishemailcheck1').value;
	}
	else{
	var optvalue = document.getElementById('publishemailcheck0').value;
	}
    	
	if(optvalue == '1'){
	
	if (isNaN(x)) {
        alert("Please enter number in stopforumspam.com allowed frequency textbox");
        return false;
    }
	else
	  {   
	    return true;
	  }
	  
	}
	
	if(optvalue == '0'){
	
	if (isNaN(x)) {
        alert("Please enter number stopforumspam.com allowed frequency textbox");
        return false;
    }
	else
	  {   
	    return true;
	  }
	  
	}
	
	
	
}

function init(){

if(document.getElementById('publishforumcheck1').checked){
document.getElementById("forumfrequency").readOnly = false;
}
else{
document.getElementById("forumfrequency").readOnly = true;
}

if(document.getElementById('publishemailcheck1').checked){
var enableopt = document.getElementById('publishemailcheck1').value;
}
else{
var enableopt = document.getElementById('publishemailcheck0').value;
}

spamemaillisting(enableopt);

}

function forumfrequencyreadonly(){

if(document.getElementById('publishforumcheck1').checked){
document.getElementById("forumfrequency").readOnly = false;
}
else{
document.getElementById("forumfrequency").readOnly = true;
}

}

function spamemaillisting(optValue){

if(optValue != null && optValue != undefined  )
{

    if(optValue == "0" || optValue.value == "0"){
        document.getElementById("BLACKLISTEMAILLISTING").style.display = "none";
        document.getElementById("stopforumspamdiv").style.display = "none";
        document.getElementById("stopforumspamfrequency").style.display = "none";
        document.getElementById("saveviewlogoptiondiv").style.display = "none";
        document.getElementById("viewlogdiv").style.display = "none";
        
        
    } else {
        document.getElementById("BLACKLISTEMAILLISTING").style.display = "";
        document.getElementById("stopforumspamdiv").style.display = "";
        document.getElementById("stopforumspamfrequency").style.display = "";
        document.getElementById("saveviewlogoptiondiv").style.display = "";
        document.getElementById("viewlogdiv").style.display = "";
    }
}

}
var j = jQuery.noConflict();
    j(document).ready(function()
    {
    j('#publishemailcheck1').css({'opacity':'0','outline':'0'});
   j('#publishemailcheck0').css({'opacity':'0','outline':'0'});
    
    j('#publishemailcheck1').bind('click', function()
    {
        j('#BLACKLISTEMAILLISTING').show();
        j('#stopforumspamdiv').show();
        j('#stopforumspamfrequency').show();
        j('#saveviewlogoptiondiv').show();
        j('#viewlogdiv').show();
    });
    
    
    j('#publishemailcheck0').bind('click', function()
    {
        j('#BLACKLISTEMAILLISTING').hide();
        j('#stopforumspamdiv').hide();
        j('#stopforumspamfrequency').hide();
        j('#publishemailcheck0').hide();
        j('#saveviewlogoptiondiv').hide();
        j('#viewlogdiv').hide();
    });
    
    j('#publishforumcheck1').bind('click', function()
    {
    
       j('#forumfrequency').attr('readonly', false);
    });
    
    j('#publishforumcheck0').bind('click', function()
    {
       j('#forumfrequency').attr('readonly', true);
    });
    
    });