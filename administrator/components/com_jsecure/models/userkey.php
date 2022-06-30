<?php
/**
 * jSecure Authentication components for Joomla!
 * jSecure Authentication extention prevents access to administration (back end)
 * login page without appropriate access key.
 *
 * @author      $Author: Ajay Lulia $
 * @copyright   Joomla Service Provider - 2016
 * @package     jSecure3.5
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @version     $Id: jsecurelog.php  $
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
 class jSecureModeluserkey  extends JModelLegacy{
	
	function __construct(){
		parent::__construct();
 	}

	function prepareUsertype(){
		
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
		 $query->select('id,title');
		 $query->from('#__usergroups');
		 $query->where('id not like 1');
		 $query->order('id ASC');
		 $db->setQuery($query);
		 $records = $db->loadObjectlist();
		 return $records;
	}
	
	function prepareUserlist($typeFilter){
		
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
		 $query->select('user_id');
		 $query->from('#__user_usergroup_map');
		 $query->where('group_id ='.$typeFilter);
		 $db->setQuery($query);
		 $user_id_object = $db->loadObjectlist();
		 $user_id = array();
		 if(!empty($user_id_object)) {
	     $i=0;
		 foreach ($user_id_object as $key => $value) { 	
		 $user_id[$i] = $user_id_object[$i]->user_id;
		 $i++;
		 }
		 $query2 = $db->getQuery(true);
		 $query2->select('user_id');
		 $query2->from('#__jsecure_keys');
		 $query2->where('user_id IN ('.implode(",",$user_id).')'); 
		 $db->setQuery($query2);
		 $existing_users =	$db->loadObjectlist();	 
   		 if(!empty($existing_users)) {
		 $existing_user_id = array();
		 $i=0;
		 foreach ($existing_users as $key => $value) { 	
		 $existing_user_id[$i] = $existing_users[$i]->user_id;
		 $i++;
		 }
		 $final_user_id = array();
		 $final_user_id = array_diff($user_id,$existing_user_id);
		 $user_id = $final_user_id;
		 }
		 if(!empty($user_id)) {
  		 $query3 = $db->getQuery(true);
		 $query3->select('id,username');
		 $query3->from('#__users');
		 $query3->where('id IN ('.implode(",",$user_id).')'); 
		 $db->setQuery($query3);
		 $user_info =	$db->loadObjectlist();	 
		 return $user_info;
		 }
		 else {
		 $user_info = array();
		 return $user_info;
		 }
		 }
		 else {
		 $user_info = array();
		 return $user_info;
		 }
	}
	
	function saveUserkeys($userID, $user_key, $start_date, $end_date, $status){
			
	     $start_date = strtotime($start_date);
		 $end_date = strtotime($end_date);
		 $user_key = md5($user_key);
		 for($i= 0; $i< count($userID); $i++ )
		 {
	     $db = JFactory::getDBO();
	     $query = $db->getQuery(true);
		 $columns = array('user_id', 'key', 'start_date', 'end_date', 'status');
		 $values = array($userID[$i], $db->quote($user_key), $start_date, $end_date, $db->quote($status));
		 $query->insert($db->quoteName('#__jsecure_keys'));
		 $query->columns($db->quoteName($columns));
		 $query->values(implode(',', $values));
		 $db->setQuery($query);
		 $db->query();
		 }
		 return true;
	}
	
	function prepareViewlist(){
		 
		 $app = JFactory::getApplication();
		 $context = JRequest::getVar('option');	
		 $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		 $limitstart = $app->getUserStateFromRequest($context.'limitstart', 'limitstart', 0, 'int');
		 $limitstart = ( $limit != 0 ? (floor($limitstart / $limit) * $limit) : 0 );
		 $db = JFactory::getDBO();
	     $query = $db->getQuery(true);
	     $query->select('*');
	     $query->from('#__jsecure_keys');
	     $query->order('user_id ASC');	//
	     $db->setQuery($query,$limitstart,$limit);
	     $records = $db->loadObjectlist();
	     if(!empty($records)){
		 return $records;
		 }
		 else{
		 return null;
		 }
	}
	
	function prepareViewnames(){
	
		 $user_id = $this->prepareViewlist();
		 $user_id_array = array();
		 if(!empty($user_id)) {
	     $i=0;
		 foreach ($user_id as $key => $value) { 	
		 $user_id_array[$i] = $user_id[$i]->user_id;
		 $i++;
		 }
		 $db = JFactory::getDBO();
	     $query = $db->getQuery(true);
	     $query->select('username');
	     $query->from('#__users');
	     $query->where('id IN ('.implode(",",$user_id_array).')'); 
		 $query->order('id ASC');	//
		 $db->setQuery($query);
	     $records = $db->loadObjectlist();
		 return $records;
		 }
		 else { 
		 return null;
		 }
 	}
	
	function prepareViewgroups(){
	
		 $user_id = $this->prepareViewlist();
		 $user_id_array = array();
		 if(!empty($user_id)) {
	     $i=0;
		 foreach ($user_id as $key => $value) { 	
		 $user_id_array[$i] = $user_id[$i]->user_id;
		 $i++;
		 }
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
		 $query->select('group_id,user_id');
		 $query->from('#__user_usergroup_map');
		 $query->where('user_id IN ('.implode(",",$user_id_array).')');
		 $query->order('user_id ASC');	//
		 $db->setQuery($query);
		 $group_id = $db->loadObjectlist();
		 $group_id_array = array();
		 if(!empty($group_id)) {
		 $i=0;
		 foreach ($group_id as $key => $value) { 	
		 $group_id_array[$i] = $group_id[$i]->group_id;
		 $i++;
		 }	
		 }
		 $user_groups = array();
		 for($i=0;$i<count($group_id_array);$i++)
		 {
		 $query2 = $db->getQuery(true);
		 $query2->select('title,id');
		 $query2->from('#__usergroups');
		 $query2->where('id ='.$group_id_array[$i]);
		 $db->setQuery($query2);
		 $user_groups[$i] = $db->loadObject();
		 }
	
		 /****************/
		 
		 $arr1 = $group_id;									//group_id arr1
		 $arr2 = $user_groups;								//user_groups arr2							
		
		 foreach($arr1 as $val)
			{
				foreach($arr2 as $value)
				{	
					
					if($value->id == $val->group_id)
					{
						if(empty($new_arr[$val->user_id]))
						{
							$new_arr[$val->user_id] = $value->title;
						}
						else
						{
							$key = $val->user_id;
							if(!strstr($new_arr[$key],$value->title))
							{
								$new_arr[$key] = $new_arr[$key]."<b>,</b> ".$value->title;
							}
							
						}
					    	
					
					}
				}
			}
			
			
		 /****************/
		 
		 if(!empty($user_groups)) {
		 $i=0;
		 foreach ($new_arr as $key => $value) { 	
		 $user_groups_array[$i] = $new_arr[$key];
		 $i++;
		 }
		 }
		 return $user_groups_array;
		 }
		 else {
		 return null;
		 }
		
	}
	
	function publishkey($id_list){
		
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
		 $query->update('#__jsecure_keys');
		 $query->set('status = "1"');
		 $query->where('id IN ('.implode(",",$id_list).')');
		 $db->setQuery($query);
		 if($db->query() == true){
		 return true;
		 }
		 
	}
	
	function unpublishkey($id_list){
		
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
		 $query->update('#__jsecure_keys');
		 $query->set('status = "0"');
		 $query->where('id IN ('.implode(",",$id_list).')');
		 $db->setQuery($query);
		 if($db->query() == true){
		 return true;
		 }
		 
	}
	
	function deletekey($id_list){
		 
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
 		 $query->delete('#__jsecure_keys');
		 $query->where('id IN ('.implode(",",$id_list).')');
		 $db->setQuery($query);
		 if($db->query() == true){
		 return true;
		 }
	}
	
	function prepareEditkey($key_id){
		
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
		 $query->select('*');
		 $query->from('#__jsecure_keys');
		 $query->where('id ='.$key_id);
		 $db->setQuery($query);
		 $data = $db->loadObject();
		 $query2 = $db->getQuery(true);
	     $query2->select('username');
	     $query2->from('#__users');
	     $query2->where('id='.$data->user_id); 
		 $db->setQuery($query2);
	     $name_object = $db->loadObject();
		 $record = array();
		 $record['key_id'] = $data->id;
		 $record['user'] = $name_object->username;
		 $record['key'] = $data->key;
		 $record['start_date'] = $data->start_date;
	   	 $record['end_date'] = $data->end_date;
	 	 $record['status'] = $data->status;
		 return $record;
	}
	
	function updateUserkeys($key_id, $user_key, $start_date, $end_date, $status){
	
		 $start_date	= strtotime($start_date);
		 $end_date = strtotime($end_date);
		 if($user_key != ''){
		 $user_key = md5($user_key);
		 }
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
		 if($user_key != ''){
		 $fields = array(
		 $db->quoteName('key') . ' = ' . $db->quote($user_key),
		 $db->quoteName('start_date') . ' = ' . $start_date,
		 $db->quoteName('end_date') . ' = ' . $end_date,
		 $db->quoteName('status') . ' = ' . $db->quote($status)
		 );
		 }
		 else{
		 $fields = array(
		 $db->quoteName('start_date') . ' = ' . $start_date,
		 $db->quoteName('end_date') . ' = ' . $end_date,
		 $db->quoteName('status') . ' = ' . $db->quote($status)
		 );
		 }
		 $conditions = array(
		 $db->quoteName('id') . ' = ' . $key_id
		 );
		 $query->update($db->quoteName('#__jsecure_keys'))->set($fields)->where($conditions);
		 $db->setQuery($query);
		 if($db->query() == true){
		 return true;
		 }
		 
	}
	
	function searchViewnames($search){
	
		 $db = JFactory::getDBO();
	     $query = $db->getQuery(true);
	     $query->select('id');
	     $query->from('#__users');
		 $query->where('username LIKE "%'. $search . '%"');
		 $query->order('id ASC');
		 $db->setQuery($query);
	     $search_user_id = $db->loadAssocList();	
		 $search_id_array = array();
		 if(!empty($search_user_id)) {
	     $i=0;
		 foreach ($search_user_id as $key => $value) { 	
		 $search_id_array[$i] = $search_user_id[$i]['id'];
		 $i++;
		 }
		 }
		 $query2 = $db->getQuery(true);
	     $query2->select('user_id');
	     $query2->from('#__jsecure_keys');
		 $query->order('user_id ASC'); //
		 $db->setQuery($query2);
		 $jsecure_user_id = $db->loadObjectlist();
		 $jsecure_id_array = array();
		 if(!empty($jsecure_user_id)) {
	     $i=0;
		 foreach ($jsecure_user_id as $key => $value) { 	
		 $jsecure_id_array[$i] = $jsecure_user_id[$i]->user_id;
		 $i++;
		 }
		 }
		 $result_id = array_intersect($search_id_array, $jsecure_id_array);
		 if(!empty($result_id)){
		 $query2 = $db->getQuery(true);
	     $query2->select('id,username');
	     $query2->from('#__users');
		 $query2->where('id IN ('.implode(",",$result_id).')');
		 $query2->order('id ASC');
		 $db->setQuery($query2);
	     $records = $db->loadObjectlist();
		 return $records;
		 }
		 else{
		 return null;
		 }
	}
	
	function searchViewgroups($search){
	
		 $user_id = $this->searchViewnames($search);
		 $user_id_array = array();
		 if(!empty($user_id)) {
	     $i=0;
		 foreach ($user_id as $key => $value) { 	
		 $user_id_array[$i] = $user_id[$i]->id;
		 $i++;
		 }
		 $db = JFactory::getDBO();
		 $query = $db->getQuery(true);
		 $query->select('group_id,user_id');
		 $query->from('#__user_usergroup_map');
		 $query->where('user_id IN ('.implode(",",$user_id_array).')');
		 $query->order('user_id ASC');
		 $db->setQuery($query);
		 $group_id = $db->loadObjectlist();
		 $group_id_array = array();
		 if(!empty($group_id)) {
		 $i=0;
		 foreach ($group_id as $key => $value) { 	
		 $group_id_array[$i] = $group_id[$i]->group_id;
		 $i++;
		 }	
		 }
		 $user_groups = array();
		 for($i=0;$i<count($group_id_array);$i++)
		 {
		 $query2 = $db->getQuery(true);
		 $query2->select('title,id');
		 $query2->from('#__usergroups');
		 $query2->where('id ='.$group_id_array[$i]);
		 $db->setQuery($query2);
		 $user_groups[$i] = $db->loadObject();
		 }
		 
		 /****************/
		 
		 $arr1 = $group_id;									//group_id arr1
		 $arr2 = $user_groups;								//user_groups arr2							
		
		 foreach($arr1 as $val)
			{
				foreach($arr2 as $value)
				{	
					
					if($value->id == $val->group_id)
					{
						if(empty($new_arr[$val->user_id]))
						{
							$new_arr[$val->user_id] = $value->title;
						}
						else
						{
							$key = $val->user_id;
							if(!strstr($new_arr[$key],$value->title))
							{
								$new_arr[$key] = $new_arr[$key]."<b>,</b> ".$value->title;
							}
							
						}
					    	
					
					}
				}
			}
			
			
		 /****************/
		 
		 if(!empty($user_groups)) {
		 $i=0;
		 foreach ($new_arr as $key => $value) { 	
		 $user_groups_array[$i] = $new_arr[$key];
		 $i++;
		 }
		 }
		 return $user_groups_array;
	     }
	     else{
	     return null;
	     }
	   
	}
	
	function searchViewlist($search){
		
		
		 $user_id = $this->searchViewnames($search);
		 $user_id_array = array();
		 if(!empty($user_id)) {
	     $i=0;
		 foreach ($user_id as $key => $value) { 	
		 $user_id_array[$i] = $user_id[$i]->id;
		 $i++;
		 }
		 $db = JFactory::getDBO();
	     $query = $db->getQuery(true);
	     $query->select('*');
	     $query->from('#__jsecure_keys');
	     $query->where('user_id IN ('.implode(",",$user_id_array).')');
		 $query->order('user_id ASC'); //
	     $db->setQuery($query);
	     $records = $db->loadObjectlist();
	     return $records;
		 }
		 else{
		 return null;
		 }
		
	}
	
	function getTotalRecords(){
		 
		 $db = JFactory::getDBO();
	     $query = $db->getQuery(true);
	     $query->select('*');
	     $query->from('#__jsecure_keys');
	     $db->setQuery($query);
	     $records = $db->loadObjectlist();
	     return count($records);
	
	}
	
}