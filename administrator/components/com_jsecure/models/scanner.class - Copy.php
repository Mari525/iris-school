<?php
/**
** Copyright © Larry Wakeman - 2013
**
** All rights reserved. No part of this publication may be reproduced, stored in a retrieval system, 
** or transmitted in any form or by any means without the prior written permission of the copyright
** owner and must contain the above copyright notice.
**
** Permission is granted to anyone but this copyright noticemust be included.
*/
class scanner {
    
    private $config = array();    // Configuration parameters
    private $filename; // filename root for this file
    private $dirname; // filename directory for this file
    private $dblink; // database link
    private $db; // Database object
    private $scan_id; // Scan id
    
/**
 ** Class Construtor
 */
    public function __construct()
    {
        $this->filename = str_replace('.php', '', str_replace('.class', '', basename(__file__)));
        $this->dirname = dirname(__file__);
        // load the config
        if (file_exists($this->dirname.'/'.$this->filename.'.ini')) {
            $fh = fopen($this->dirname.'/'.$this->filename.'.ini', 'r');
            $this->config = unserialize(base64_decode(fgets($fh)));
            fclose($fh);
        }
        //initialize the database
        if (count($this->config) != 0) $this->dbConnect();
    }
    
/**
 ** initialize the database
 */
    function dbConnect() {
        $this->dblink = mysqli_connect($this->config['hostname'],$this->config['username'], $this->config['password'], $this->config['database']);
        if (!$this->dblink) {
            die ('Could not connect: ' . mysqli_error($this->dblink));
        }
    }
    
/**
 ** Escape String
 **/
		private function excape_string($string) {
      return mysql_real_escape_string($string);
		}
/**
 ** Execute a sql query
 */
 
	private function jooquery($query) {
	  
		$db = JFactory::getDbo();
	  
      if (substr(strtoupper ($query) , 0, 6) == 'SELECT' || substr(strtoupper ($query) , 0, 4) == 'SHOW') {
        
		$db->setQuery($query);
		$return = $db->loadObjectList();
		
		
        return $return;
      }
      if (substr(strtoupper ($query) , 0, 6) == 'INSERT') {
        
		$db->setQuery($query);
				$db->query();
		
		return $db->insertid();;
      }
	  
	  $db->setQuery($query);
	  $db->query();
    }
 
    private function query($query, $line=0) {
	    // insure db connection is good
	    if (!mysqli_ping ($this->dblink)) {
				mysqli_close();
				$this->dbConnect();
	    }
	    // perform query
      $result = mysqli_query($this->dblink, $query);
      if (!$result) {
        die('Invalid query: ' . mysqli_error($this->dblink).'<br>'.$query.'<br>'.basename(__FILE__).' '.$line.'<br></div>');
      }
      // return data or id as appropriate
      if (substr(strtoupper ($query) , 0, 6) == 'SELECT' || substr(strtoupper ($query) , 0, 4) == 'SHOW') {
        $return = array();
        if (mysqli_num_rows($result) != 0) {
          for ($i = 0; $i < mysqli_num_rows($result); $i++) {
            $return[] = mysqli_fetch_assoc($result);
          }
        }
        return $return;
      }
      if (substr(strtoupper ($query) , 0, 6) == 'INSERT') {
        return mysqli_insert_id($this->dblink);
      }
    }
    
/**
 ** Get the configuration
 */
    public function get_config() {
        return $this->config;
    }
    
/**
 ** Set the configuration
 */
    public function set_config($config) {
        // save the new config
        $this->config = $config;
        // write the ini file
        $saveConfig = base64_encode(serialize($config));
        $fh = fopen($this->dirname.'/'.$this->filename.'.ini', 'w');
        fwrite($fh, $saveConfig."\n");
        fclose ($fh);
        // create the tables, if needed
        $this->dbConnect();
        $query = "SHOW TABLES LIKE '".$this->config['dbtable']."_scan_run'";
        $data = $this->query($query, __LINE__);
        if (is_null($data) || count($data) == 0) {
            $query = "CREATE TABLE `".$this->config['dbtable']."_scan_run` (
                `scan_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `scantime` TIMESTAMP NOT NULL  DEFAULT CURRENT_TIMESTAMP
                ) ENGINE = MYISAM   DEFAULT CHARSET=".$this->config['char_set'].";";
            $data = $this->query($query, __LINE__);
        }
        $query = "SHOW TABLES LIKE '".$this->config['dbtable']."_result'";
        $data = $this->query($query, __LINE__);
        if (is_null($data) || count($data) == 0) {
            $query = "CREATE TABLE `".$this->config['dbtable']."_result` (
                `result_id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `scan_id` BIGINT NOT NULL ,
                `filename` VARCHAR( 255 ) CHARACTER SET ".$this->config['char_set']." COLLATE ".$this->config['dbcollat']." NOT NULL ,
                 `status` enum('1 - Initial','2 - Unchanged','3 - Changed','4 - Added','5 - Deleted') NOT NULL DEFAULT '1 - Initial',
                `hash` VARCHAR( 17 ) CHARACTER SET ".$this->config['char_set']." COLLATE ".$this->config['dbcollat']." NOT NULL
                ) ENGINE = MYISAM   DEFAULT CHARSET=".$this->config['char_set'].";";
            $data = $this->query($query, __LINE__);
        }
        $query = "SHOW TABLES LIKE '".$this->config['dbtable']."_temp'";
        $data = $this->query($query, __LINE__);
        if (is_null($data) || count($data) == 0) {
            $query = "CREATE TABLE `".$this->config['dbtable']."_temp` (
                `filename` VARCHAR( 255 ) CHARACTER SET ".$this->config['char_set']." COLLATE ".$this->config['dbcollat']." PRIMARY KEY
                ) ENGINE = MYISAM   DEFAULT CHARSET=".$this->config['char_set'].";";
            $data = $this->query($query, __LINE__);
        }
    }
    
/**
 ** Perform initial scan
 */
    public function initial() {
        $query = "TRUNCATE TABLE `".$this->config['dbtable']."_scan_run`";   
        $data = $this->query($query, __LINE__);
        $query = "TRUNCATE TABLE `".$this->config['dbtable']."_result`";   
        $data = $this->query($query, __LINE__);
        $query = "TRUNCATE TABLE `".$this->config['dbtable']."_temp`";   
        $data = $this->query($query, __LINE__);
        $query = "INSERT INTO `".$this->config['dbtable']."_scan_run` VALUES (NULL, NULL)";
        $this->scan_id = $this->query($query, __LINE__);
        $return = false;
        if ($this->doTree('./', 'initial')) 
            $return = true;
        $query = "TRUNCATE TABLE `".$this->config['dbtable']."_temp`";   
        $data = $this->query($query, __LINE__);
        return $return;
    }
    
/**
 ** Perform scan
 */
    public function scan($configuration) {
    	$results['current_scan'] = time();
      $query = "TRUNCATE TABLE #__jsecure_temp";   
      $this->jooquery($query);
	  //$this->query($query, __LINE__);
      
	  $query = "INSERT INTO #__jsecure_scan_run VALUES (NULL, NULL)";
      //$this->scan_id = $this->query($query, __LINE__);
      $this->scan_id = $this->jooquery($query);
	  
	  if ($this->doTree('./templates','scan',$configuration)){
	    			
			$return = array('Changed' => array(), 'Added' => array(), 'Deleted' => array());
	    	$query = "SELECT scantime FROM #__jsecure_scan_run WHERE scan_id = ".$this->scan_id;
      	//$results = $this->query($query, __LINE__);
    		$results = $this->jooquery($query);
			
			$return['current_scan'] = $results[0]->scantime;
			
			//$return['current_scan'] = $results['0']['scantime'];
        $query = "SELECT * FROM #__jsecure_result 
        	WHERE scan_id = ".$this->scan_id."
        	ORDER BY status, filename"; 
      	//$results = $this->query($query, __LINE__);
      	
		$results = $this->jooquery($query);
		
		foreach ($results as $result) {
        	if ($result->status == '4 - Added') $return['Added'][] = $result->filename;
        	else if ($result->status == '3 - Changed') $return['Changed'][] = $result->filename;
        	else echo $result->status.', '.$result->filename.'<br>';
      	}
        $query = "SELECT DISTINCT filename FROM #__jsecure_result 
        	WHERE filename NOT IN (SELECT filename FROM #__jsecure_temp)";
			// AND scan_id = ".$this->scan_id;
      	
		//$results = $this->query($query, __LINE__);
      	$results = $this->jooquery($query);
		
		foreach ($results as $result) {
			$query = "SELECT * FROM #__jsecure_result
	        	WHERE filename = '".$result->filename."' ORDER BY scan_id DESC LIMIT 0,1";
      		//$check = $this->query($query, __LINE__);
      		$check = $this->jooquery($query);
			
			if($check[0]->status != '5 - Deleted'){
        		$return['Deleted'][] = $result->filename;
			      				  
				$query = "INSERT INTO #__jsecure_result VALUES(null, ".$this->scan_id.", '".$result->filename."', '5 - Deleted',0)";
			    //$result_id = $data = $this->query($query, __LINE__);
				$result_id = $data = $this->jooquery($query);			
			}			
      	}
					
        return $return;
      }
      return false;
  }
    
/**
 ** Calculate the checksum
 */
	private function checksum($file) {
	    
		
		
		$ignores = Array(10, 13);
	    $fh = fopen($file, 'r');
	    $buffer = '';
	    while (!feof($fh)) {
	        $buffer .= fgets($fh);
	    }
	    fclose ($fh);
	    foreach ($ignores as $ignore) {
	        while (strpos($buffer, chr($ignore))) {
	            $buffer = str_replace(chr($ignore), '', $buffer);
	        }
	    }
		
		// if($file == './templates/hathor/LICENSE.txt'){
		
		// $hashval = hash('crc32', $buffer).hash('crc32b', $buffer);
		
		// echo 'omkar'; 
		// echo '<br/>';
		// echo $hashval; die;
		// }
		
		
		$hashval = hash('crc32', $buffer).hash('crc32b', $buffer);
		
	    return hash('crc32', $buffer).hash('crc32b', $buffer);
	}
    
/**
 ** Log the result
 */
	private function log_hash($file, $hash, $scantype) {
	
		$file = $this->excape_string(str_replace('//', '/', $file));
		
		
		
		$query = "SELECT * FROM #__jsecure_temp WHERE filename = '".$file."'";
    //$array = $this->query($query, __LINE__);
    $array = $this->jooquery($query);
	
	if (count($array) == 0) {
	    $query = "INSERT INTO #__jsecure_temp VALUES ('".$file."')";
	    //$this->query($query, __LINE__);
			$this->jooquery($query);
		
			
			if ($scantype == 'initial') {
	      $query = "INSERT INTO #__jsecure_result VALUES (null, ".$this->scan_id.", '".$file."', '1 - Initial', '".$hash."')";
	      //$result_id = $data = $this->query($query, __LINE__);
			$result_id = $data = $this->jooquery($query);
			
			} else if ($scantype == 'scan') {
				$query = "SELECT `hash`, `status` 
					FROM #__jsecure_result 
					WHERE `filename` = '".$file."'
					ORDER BY `result_id` DESC
					LIMIT 0, 1";
				//$data = $this->query($query, __LINE__);
				$data = $this->jooquery($query);
				
			if(empty($data)) {
		      $query = "INSERT INTO #__jsecure_result VALUES (null, ".$this->scan_id.", '".$file."', '4 - Added', '".$hash."')";
		      
				$result_id = $data = $this->jooquery($query);
				
				} else if($data[0]->hash != $hash) {
					$status = '3 - Changed';
					
										
				if ($data[0]->status == '5 - Deleted') $status = '4 - Added';	
				
				
			/*	
				
				if ($data) {
		      $query = "INSERT INTO #__jsecure_result VALUES (null, ".$this->scan_id.", '".$file."', '4 - Added', '".$hash."')";
		      
				$result_id = $data = $this->jooquery($query);
				
				} else if($data['0']['hash'] != $hash) {
					$status = '3 - Changed';
									
					if ($data['0']['status'] == '5 - Deleted') $status = '4 - Added';
		     */ 
			  
			  
			  $query = "INSERT INTO #__jsecure_result VALUES (null, ".$this->scan_id.", '".$file."', '".$status."', '".$hash."')";
		      
			  //$result_id = $data = $this->query($query, __LINE__);
				$result_id = $data = $this->jooquery($query);
				
				}
			}
		}
	}
    
/**
 ** scan the site
 */
	private function doTree($dir, $scantype,$configuration) {
    	$dir = str_replace('//', '/', $dir);
	    $dirs = explode(',',$configuration['ignoreDir']);
	    foreach ($dirs as $entry) if (stripos($dir, trim($entry)) !== false)   return true;
	    $filetypes = explode(',',$configuration['includeType']);
	    if ($dh = opendir($dir)) {
	        while ($file = readdir($dh)) {
	            if ($file != '.' && $file != '..') {
	                if (is_dir($dir.'/'.$file)) {
	                    if (!$this->doTree($dir.'/'.$file, $scantype)) {
	                        return false;
	                    }
	                } else {
	                    if (filesize($dir.'/'.$file)) {
	                        foreach ($filetypes as $type) {
	                            if (strpos($file, '.'.trim($type)) !== false ) {
	                                set_time_limit(30);
	                                $this->log_hash($dir.'/'.$file, $this->checksum( $dir.'/'.$file ), $scantype);
	                            }
	                        }
	                    }
	                }
	            }
	        }
	        return true;
	    } else {
	        echo 'error opening '.$dir.'</h3>';
	        return false;
	    }
	}

}
