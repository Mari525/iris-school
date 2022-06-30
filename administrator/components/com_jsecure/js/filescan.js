Joomla.submitbutton = function(pressbutton){
	var submitForm = document.adminForm;
	
	if (pressbutton == 'cancel') {
    submitForm.task.value = pressbutton;
    submitForm.submit();
    return true;                               
    }

	if(pressbutton=="help"){
		submitForm.task.value=pressbutton;
		submitForm.submit();
		return true;
	}
	
}

function move() {
    var elem = document.getElementById("myBar"); 
    var width = 1;
    var id = setInterval(frame,1000);
    function frame() {
        if (width >= 99) {
            //clearInterval(id);
        } else {
            width++; 
            elem.style.width = width + '%'; 
            document.getElementById("label").innerHTML = width * 1 + '%';
        }
    }
}

function startscanner(){
	/* move(); */
  document.getElementById('loader').style.display = "block";		
	
  var submitForm = document.adminForm;	
  submitForm.task.value="startscanner";
  submitForm.submit();
  return true;	
}



