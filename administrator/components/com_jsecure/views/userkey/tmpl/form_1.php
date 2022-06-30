<?php
defined('_JEXEC') or die('Limited Access!');
$document = JFactory::getDocument();
JHtml::_('behavior.framework', true);
include JPATH_COMPONENT_ADMINISTRATOR.'/'.'helpers'.'/'.'jsecureadminmenu.php';
//JHtml::_('behavior.formvalidation');
$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/validate.js"></script>');
JHTML::_('script','system/modal.js', false, true);
JHTML::_('stylesheet','system/modal.css', array(), true);

$document->addCustomTag('<script language="javascript" type="text/javascript" src="components/com_jsecure/js/userkey.js"></script>');
$task	= JRequest::getCmd('task');
$document->addScriptDeclaration("window.addEvent('domready', function() {
		$$('.hasTip').each(function(el) {
			var title = el.get('title');
			if (title) {
				var parts = title.split('::', 2);
				el.store('tip:title', parts[0]);
				el.store('tip:text', parts[1]);
			}
		});
		var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
	});
");
?>
<script>
/* custom js code for enabling form validation */
	
	Joomla.submitbutton = function(pressbutton){
		var submitForm = document.adminForm;
		if (pressbutton == 'cancelkeyform') {
			 submitForm.task.value = pressbutton;
			 submitForm.submit();
			 return true;								// bypass form validation on cancel
		  }
		if (document.formvalidator.isValid(submitForm)) {
			submitForm.task.value = pressbutton;
			document.getElementById('system-messageMAIN').style.display ="none";
			 submitForm.submit();
		   }
		   
		else{
		
			/* START - Condition to hide the below succeeding validation message */
			if(pressbutton != 'applyEditkey' && pressbutton != 'saveEditkey'){
			
			var user_group_filter = document.getElementById('user_group_filter').value;	
			var user_key = document.getElementById('user_key').value;	
			var start_date = document.getElementById('start_date').value;	
			var end_date = document.getElementById('end_date').value;	
			var publish1 = document.getElementById('publish1').checked;	
			var publish0 = document.getElementById('publish0').checked;	
			var a = document.getElementById("adminForm").elements;
			var count = 0;

		   for(i=0;i<a.length;++i)
			{
				if(a[i].type=='checkbox' && a[i].checked)
				{ 
				 count = count + 1;
				}
			}
			
			// console.log('user_group_filter->'+user_group_filter);   // use this for debugging
			// console.log('checkbox_count->'+count);
			// console.log('user_key->'+user_key);
			// console.log('start_date->'+start_date);
			// console.log('end_date->'+end_date);
			// console.log('publish1->'+publish1);
			// console.log('publish0->'+publish0);
			
			/* END   - Condition to hide the below succeeding validation message */
			
				if( user_group_filter == '' || count == 0 || user_key == '' || start_date == '' || end_date == '' || ( publish1 == false && publish0 == false )){
				
				if(pressbutton != 'applyEditkey' && pressbutton != 'saveEditkey'){
				var msg = new Array();
				msg.push('<?php echo JText::_('All the fields marked with (*) are mandatory')?>');
				document.getElementById('system-messageMAIN').innerHTML = msg.join('\n'); 
				document.getElementById('system-messageMAIN').style.display ="block";
				document.getElementById('system-message').style.display ="none";  // hide default system-message div
				}
				else{
				document.getElementById('system-message').style.display ="none";
				}
				
		  }
		  
			else{
			document.getElementById('system-messageMAIN').style.display ="none";
			document.getElementById('system-message').style.display ="none";
		  }
         }
		 else{
			document.getElementById('system-messageMAIN').style.display ="none";
			document.getElementById('system-message').style.display ="none";
		  }	
	   }	
	} 
/* custom js code for enabling form validation */	

window.addEvent('domready', function(){
   document.formvalidator.setHandler('checkbox', function(value) {
     var a = document.getElementById("adminForm").elements;
	 var count = 0;
	  //alert(a.length);
	   for(i=0;i<a.length;++i)
		{
			if(a[i].type=='checkbox' && a[i].checked)
			{ 
			 count = count + 1;
			}
		}
		
		if(count == 0)
		{
			var msg = new Array();
            // msg.push('Invalid User Selection:');           
			// msg.push('<?php echo JText::_('Please select user(s) to assign jSecure keys')?>');
			// document.getElementById('system-message1').innerHTML = msg.join('\n'); 
			// document.getElementById('system-message1').style.display ="block";
			document.getElementById('user-label').style.color = "#9D261D";
			document.getElementById('user-label').style.fontWeight = "bold";
			document.getElementById('checkbox_div').style.border= "thin solid #9D261D";
			return false;
		}
		else
		{ 
			//document.getElementById('system-message1').style.display ="none";
			document.getElementById('user-label').style.color = "#333";
			document.getElementById('user-label').style.fontWeight = "normal";
			document.getElementById('checkbox_div').style.border= "thin solid lightgrey";
			return true;
		}
   });
});

window.addEvent('domready', function(){
   document.formvalidator.setHandler('dateValidator', function(value) {
     var start = document.getElementById("start_date").value;
     var end   = document.getElementById("end_date").value;
	 
	 if(start != '' && end != ''){
		
		
		start=start.split("-");
		var startDate=start[1]+"/"+start[0]+"/"+start[2];
		var start_timestamp = new Date(startDate).getTime();
		
		end=end.split("-");
		var endDate=end[1]+"/"+end[0]+"/"+end[2];
		var end_timestamp = new Date(endDate).getTime();
		
		if(start_timestamp > end_timestamp){
			
			var msg = new Array();           
			msg.push('<?php echo JText::_('The end date cannot be less than start date')?>');
			document.getElementById('system-message2').innerHTML = msg.join('\n'); 
			document.getElementById('system-message2').style.display ="block";
			return false;
		}
		else{
			document.getElementById('system-message2').style.display ="none";
			return true;
		}
		
	 }
	 
   });
});

window.addEvent('domready', function(){
   document.formvalidator.setHandler('radio', function(value) {
     var publish1 = document.getElementById("publish1").checked;
     var publish0   = document.getElementById("publish0").checked;
	 
	 if(publish1 != 0 || publish0 != 0){
			//document.getElementById('system-message3').style.display ="none";
			document.getElementById('status-label').style.color = "#333";
			document.getElementById('status-label').style.fontWeight = "normal";
			return true;
	 }
	 else{
			var msg = new Array();
            // msg.push('Invalid Status Selection:');           
			// msg.push('<?php echo JText::_('status cannot be empty')?>');
			// document.getElementById('system-message3').innerHTML = msg.join('\n'); 
			// document.getElementById('system-message3').style.display ="block";
			document.getElementById('status-label').style.color = "#9D261D";
			document.getElementById('status-label').style.fontWeight = "bold";
			return false;
	 } 
	 
   });
});

window.addEvent('domready', function(){
   document.formvalidator.setHandler('emptySpace', function(value) {
     var password = document.getElementById("user_key").value;
	 
	 if(!alphanumeric(password)){
		password="";
		var msg = new Array();
		msg.push('<?php echo JText::_('User Key cannot contain blank spaces or special characters')?>');
		document.getElementById('system-message4').innerHTML = msg.join('\n'); 
		document.getElementById('system-message4').style.display ="block";
		adminForm.user_key.focus();
		return false;
	 }
	 
	 else if(alphanumeric(password) == 5){
		var msg = new Array();
		msg.push('<?php echo JText::_('User Key cannot be less than 5 and more than 20 characters')?>');
		document.getElementById('system-message4').innerHTML = msg.join('\n'); 
		document.getElementById('system-message4').style.display ="block";
		adminForm.user_key.focus();
		return false;
	 }
	 
	 else{
		document.getElementById('system-message4').style.display ="none";
		return true;
	 }
   });
});
function alphanumeric(keyValue){
	
	var numerics = keyValue;
	for(var j=0; j<numerics.length; j++){
		  var alphas = numerics.charAt(j);
		  var hh = alphas.charCodeAt(0);
		  if(!((hh > 47 && hh<58) || (hh > 64 && hh<91) || (hh > 96 && hh<123))){
		  	return false;
		  }
		  else if(numerics.length < 5 || numerics.length > 20){
			return 5;
		  }
	}
	return true;
}
function noUSER(){

	document.getElementById('user-label').style.color = "#9D261D";
	document.getElementById('user-label').style.fontWeight = "bold";

	return false;
}
</script>
<div class=""><?php JsecureAdminMenuHelper::addSubmenu(''); ?></div>
<form class="form-validate" action="index.php?option=com_jsecure&task=userkey" method="post" name="adminForm" id="adminForm" autocomplete="off" class="span10" >
<p style="display:none;" id="system-message1"class="alert alert-error"></p>
<p style="display:none;" id="system-message2"class="alert alert-error"></p>
<p style="display:none;" id="system-message3"class="alert alert-error"></p>
<p style="display:none;" id="system-message4"class="alert alert-error"></p>
<p style="display:none;" id="system-messageMAIN"class="alert alert-error"></p>
<div class="col100">
	<fieldset class="adminform">
		<legend><?php echo ($task == 'addkey')? JText::_( 'Add User Key' ) : JText::_( 'Edit User Key ' ); ?></legend>

		<table class="table table-striped">
		<tr>
	    <td class="paramlist_value">
				<label id="user-label" class="bold hasTip" title="<?php echo JText::_('User').'::'.JText::_('USER_NAME_DESCRIPTION'); ?>" for="user_group_filter">
					<?php echo ($task == 'addkey')? JText::_( 'User(s)' ): JText::_( 'User' ); ?> *
				</label>
			</td>
		
<?php	if($task == 'addkey')
		{
?>	<td>
		
		<div id="filter-bar" class="btn-toolbar">
		<select class="input-medium required" id="user_group_filter" type="list" onchange="this.form.submit()" name="user_group_filter">
		<option value="" > - Filter user type - </option>
		<?php if(!empty($this->userType)){
		$i=0;
				foreach ($this->userType as $key => $value)
					{ 	
						
						?><option <?php echo ($this->typeFilter == $this->userType[$i]->id)? 'selected="selected"':''; ?> value="<?php echo $this->userType[$i]->id; ?>"><?php echo $this->userType[$i]->title; ?></option><?php
						
						$i++;
						
					}
				}
		?>
		</select>
		
		<button style="margin-left: 8px;margin-top: -9px;" class="btn tip" title="Clear filter result" type="button" onclick="document.id('user_group_filter').value='';this.form.submit();"><i class="icon-remove"></i></button>
		
	</div>
	 
	 
	<?php if(isset($this->userList)) {
				
				
				
				if(count($this->userList) != '0') {	
				echo'<div id="checkbox_div" style=" 
						width:147px;
						height:71px;
						border:thin solid lightgrey;
						overflow-x:scroll;
						overflow-y:scroll;
						white-space: nowrap;">';
				 $i=0;
				 foreach ($this->userList as $key => $value) { 	
				 ?><input class="required validate-checkbox" type="checkbox" style="margin-top: -2px;margin-left: 5px;" id="userID[<?php echo $i; ?>]" name="userID[]" value="<?php echo $this->userList[$i]->id; ?>"><?php echo "&nbsp;&nbsp;<b>".$this->userList[$i]->username."</b><br>"; 
				 $i++;
				 }
				echo'</div>';
			}
			
			
			else {
				?>	<script type="text/javascript">
					noUSER();
					</script>
				<label for="no-user" style="display:none;" >User(s) - no user available in this usergroup to assign key </label><input type="hidden" class="required" name="no-user" id="no-user" value="" /><?php
				echo"<p><b>No users are available for this group</b></p>"; 
				echo"You have either assigned keys to all users<br> belonging to this group or no user exists in this group";
			}
		}
		?>
			
</td>
<?php	}
		else if($task == 'editkey')
		{
?>		<td class="paramlist_value">
			<input type="text" id="user_names" name="user_names" value="<?php echo $this->keyData['user'];  ?>" readonly><br>
		</td>
<?php	}
?>
		</tr>

		<tr>
			<td class="paramlist_value">
				<label class="bold hasTip" title="<?php echo JText::_('User Key').'::'.JText::_('USER_KEY_DESCRIPTION'); ?>" for="user_key">
					<?php echo JText::_( 'User Key' ); if($task == 'addkey'){ ?> *<?php } ?>
				</label>
			</td>
			<?php if($task == 'addkey') { ?>
			<td class="paramlist_value">
				<input class="required validate-emptySpace" type="password" name="user_key" id="user_key" size="32" maxlength="250" value="<?php echo isset($this->Session_user_key)?$this->Session_user_key:'';?>" />
			</td>
			<?php } else if($task == 'editkey') { ?>
			<td class="paramlist_value">
				<input class="validate-emptySpace" type="password" name="user_key" id="user_key" size="32" maxlength="250" value="" />
			</td>
			<?php } ?>
		</tr>
		
		<tr>
			<td class="paramlist_value">
				<label class="bold hasTip" title="<?php echo JText::_('Start Date').'::'.JText::_('START_DATE_DESCRIPTION'); ?>" for="start_date">
					<?php echo JText::_( 'Start Date' ); ?> *
				</label>
			</td>
			<td class="paramlist_value">
				<?php  if($task == 'addkey'){ 
						
						if(isset($this->Session_start_date)){
							echo JHTML::calendar($this->Session_start_date,'start_date','start_date','%d-%m-%Y',array('readonly'=>'true' , 'class'=>"required")); 
						}
						else{
							echo JHTML::calendar('','start_date','start_date','%d-%m-%Y',array('readonly'=>'true' , 'class'=>"required")); 
						}
			           }
					   else if($task == 'editkey'){
							$start_date = date("d-m-Y",$this->keyData['start_date']);
							echo JHTML::calendar($start_date,'start_date','start_date','%d-%m-%Y',array('readonly'=>'true' , 'class'=>"required")); 
			           }
				?>
			</td>
		</tr>
		
		<tr>
			<td class="paramlist_value">
				<label class="bold hasTip" title="<?php echo JText::_('End Date').'::'.JText::_('END_DATE_DESCRIPTION'); ?>" for="end_date">
					<?php echo JText::_( 'End Date' ); ?> *
				</label>
			</td>
			<td class="paramlist_value">
				<?php 
				        if($task == 'addkey'){ 
						 if(isset($this->Session_end_date)){
							echo JHTML::calendar($this->Session_end_date,'end_date','end_date','%d-%m-%Y',array('readonly'=>'true' , 'class'=>"required validate-dateValidator")); 
						 }
						 else{
							echo JHTML::calendar('','end_date','end_date','%d-%m-%Y',array('readonly'=>'true' , 'class'=>"required validate-dateValidator"));
						 }
						}
						else if($task == 'editkey'){
							$end_date = date("d-m-Y",$this->keyData['end_date']);
							echo JHTML::calendar($end_date,'end_date','end_date','%d-%m-%Y',array('readonly'=>'true' , 'class'=>"required validate-dateValidator")); 
						}
				?>
			</td>
		</tr>
		
		<tr>
		<td class="paramlist_value">
		<label class="bold hasTip" id="status-label" title="<?php echo JText::_('Status').'::'.JText::_('KEY_STATUS_DESCRIPTION'); ?>" for="status">
			<?php echo JText::_( 'Status' ); ?> *
		</label>
		</td>
		
        <td class="paramlist_value">
		<fieldset id="jform_home" name="jform_home" class="radio btn-group">
			
		<?php if($task == 'addkey'){ ?>
			<input class="validate-radio" type="radio" name="status" value="1" <?php echo ( isset($this->Session_status) && $this->Session_status == 1 )? 'checked="checked"':''; ?> id="publish1" />
  			<label class="btn" for="publish1">Published</label>
  			<input class="validate-radio" type="radio" name="status" value="0" <?php echo ( isset($this->Session_status) && $this->Session_status == 0 )?  'checked="checked"':''; ?> id="publish0" />
  			<label class="btn" for="publish0">Unpublished</label>
		<?php } 
			else if($task == 'editkey'){ ?>
			<input type="radio" name="status" value="1" <?php echo ($this->keyData['status'] == 1)? 'checked="checked"':''; ?> id="publish1" />
  			<label class="btn" for="publish1">Published</label>
  			<input type="radio" name="status" value="0" <?php echo ($this->keyData['status'] == 0)?  'checked="checked"':''; ?> id="publish0" />
  			<label class="btn" for="publish0">Unpublished</label>
		<?php } ?>
		</fieldset>
		 </td>		 
		 
		</tr>
	</table>
	</fieldset>
</div>

<div class="clr"></div>
<input type="hidden" name="option" value="com_jsecure" />
<input type="hidden" name="controller" value="userkey"/>
<input type="hidden" name="task" value="addkey" />

<?php if($task == 'editkey'){ ?>
<input type="hidden" name="key_id" value="<?php echo $this->keyData['key_id'] ; ?>" />
<?php } ?>

</form>