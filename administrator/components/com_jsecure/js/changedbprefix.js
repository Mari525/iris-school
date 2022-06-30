

function dbbackup(){
	
	var submitForm = document.adminForm;
	submitForm.task.value="dbbackup";
	submitForm.submit();
	 return true;
	}

function getdbprefix(){
	document.getElementById("toolbar-cancel").style="display:none";

   var domain_value = document.getElementById('db_prefix').value;
	//var domain_value = x.replace(domain,'');
   var iChars = "`~!@#$%^&*()+=[]\\\';/{}|\":<>?.-";
   var flag = 1;
   var checked = document.getElementById('generateKey').checked;
   
   
if(domain_value.trim() == ''){
       if(checked == true){
           		   
		   flag = 2;
		   
       }
       else{
		alert('Please Enter Database Prefix');
		flag = 0;
		}
  }
   
   if(domain_value){
           for (var i = 0; i < domain_value.length; i++) {
             if (iChars.indexOf(domain_value.charAt(i)) != -1) {
               alert ("Special characters not allowed.");
               flag = 0;
               }
			   
			  if(flag == 0){
			    document.getElementById("toolbar-cancel").style="display:block";
				return false;
			  } 
			 }
   }
   
   if(flag == 0){
	   document.getElementById("toolbar-cancel").style="display:block";
       return false;
   }
   else{
  
var submitForm = document.adminForm;
    if(flag == 2){
    submitForm.task.value="generatedbprefix";
    }else{
    submitForm.task.value="submitdbprefix";
    }
    submitForm.submit();
    return true;
	}
	
	}
	
	
function CheckCheckboxes(){
   var txt = document.getElementById('db_prefix');
   var chk = document.getElementById('generateKey').checked;
   if(chk == true)
   {
         txt.value = "";
         txt.disabled = true;
        
   }
   else
   {
        txt.disabled = false;
   }
}
	