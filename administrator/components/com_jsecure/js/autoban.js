/* Check controller.php for Autoban Tasks(save,apply,cancel) */


function abipLising(optValue){
if(optValue.value != null && optValue.value != undefined  )
{
	if(optValue.value == "0"){
		document.getElementById("ABIPDDL").style.display = "none";
		document.getElementById("ABIPTRY").style.display = "none";
		document.getElementById("ABIPLISTING").style.display = "none";
	} else {
		document.getElementById("ABIPDDL").style.display = "";
		document.getElementById("ABIPTRY").style.display = "";
		document.getElementById("ABIPLISTING").style.display = "";
	}
}
}