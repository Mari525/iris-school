var AjaxObj = new Object();
AjaxObj.showMessage=1;
AjaxObj.Message='';
AjaxObj.favouriteId = 0;
AjaxObj.Request = function(url, callbackMethod)
{
	AjaxObj.request = AjaxObj.createRequestObject();
	AjaxObj.request.onreadystatechange = callbackMethod;
	AjaxObj.request.open("POST", url, true);
	AjaxObj.request.send(url);
}

AjaxObj.setMessage = function (message)
{
	AjaxObj.Message=message;
}

AjaxObj.setShowMessage = function (e)
{
	AjaxObj.showMessage=m;
}

AjaxObj.createRequestObject = function()
{
	var obj;
 	if(window.XMLHttpRequest)
 	{
  		obj = new XMLHttpRequest();
 	}
 	else if(window.ActiveXObject)
 	{
  		obj = new ActiveXObject("MSXML2.XMLHTTP");
 	}
 	return obj;
}
AjaxObj.CheckReadyState = function(obj)
{
 	if( obj.readyState == 4 )
 	{ 
  		return true;
 	} 
	return false;
}

/* End AjaxObj Code */


function showUpdates()
{
    var k  = jQuery.noConflict();
        k('#image').show();
	k('#version').hide();
	k('#notes').hide();
	var strParam="index.php?option=com_jsecure&task=getVersion";
	AjaxObj.Request(strParam,generateUpdates);
	return false;
}

function generateUpdates()
{
	if(AjaxObj.CheckReadyState(AjaxObj.request))
	{
		var k = jQuery.noConflict();	
		var extensionVersion = AjaxObj.request.responseText;
                //var extensionVersion = 3.2;
                k.ajax({
		url:"http://www.joomlaserviceprovider.com/index.php?option=com_extensionversion&task=getVersionInfo&extension=jsecure",
		dataType: 'jsonp', 
		success:function(json)
		{ 
                    
                    
			if(extensionVersion < json.version){
			var version="<p style='text-align: left;'>New Version Available - "+json.version+"..Visit Website to get latest version</p>";
			var notes= json.notes;
			k('#version').html(version);
			k('#notes').html(notes);
		}
		
		else
		{
			var version="<p style='text-align: left; color:#fff'>Version is up to date</p>";
			var notes= json.notes;
			k('#version').html(version);
			k('#notes').html(notes);
			k('#show_notes').hide();
			
		}
		},
                error:function(){
		alert("Error");
		},
		});
    k('#image').hide();
    k('#version').show();
    k('#notes').show();

	}

}
Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}	
	if(submitForm.master_password!= undefined)
   {
	if(submitForm.master_password.value == ""){
		alert("The password you've supplied is not correct.");
		submitForm.key.focus();
		return false;
	}
	
	}
	
	submitForm.task.value=pressbutton;
	submitForm.submit();
}

//function show_confirm()
   //{
   //alert("tet");
  
  // }

