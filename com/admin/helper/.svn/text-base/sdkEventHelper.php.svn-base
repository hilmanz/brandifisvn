<?php

class sdkEventHelper{
	
	
	function __construct($apps){
	global $logger,$CONFIG;
	$this->logger = $logger;
	$this->apps = $apps;
	if($this->apps->isUserOnline())  {
			if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
	}
	else $this->uid = 0;
	$this->dbshema = "inorout";
	/*0; unread, 1: deleted, 2:read and send */
	}
	
	function brandlist(){
		
		$sql = "SELECT ce.brand, ct.brandname
				FROM customize_event ce
				LEFT JOIN customize_templates ct ON ce.brand = ct.brand
				GROUP BY ct.brand";
		$result = $this->apps->fetch($sql,1);
		$no = 1;
		foreach($result as $key => $val){
			$result[$key]['no'] = $no;
			$no++;
		}
		return $result;
	
	}
	
	function eventlist(){
	
		$brand = $this->apps->_g('brand');
		
		$sql = "SELECT * FROM customize_event WHERE brand = {$brand}
				AND parentid =0";
		$result = $this->apps->fetch($sql,1);
		$no = 1;
		foreach($result as $key => $val){
			$result[$key]['no'] = $no;
			$no++;
		}
		return $result;
	
	}
	
	function flowlist(){
	
		$event = $this->apps->_g('event');
		$parent = $this->apps->_g('parent');
		
		$sql = "SELECT * FROM customize_event WHERE brand = {$event}
				AND parentid = {$parent}";
		$result = $this->apps->fetch($sql,1);
		$no = 1;
		foreach($result as $key => $val){
			$result[$key]['no'] = $no;
			$no++;
		}
		return $result;
	
	}
	
	function createlist(){
	
		$brand = $this->apps->_g('brand');
		$flowid = $this->apps->_g('flowid');
		
		$sql = "SELECT ce.*, tg.* FROM customize_event ce LEFT JOIN tbl_games_references tg 
				ON ce.schemaid = tg.schemaid WHERE ce.brand = {$brand}
				AND ce.id = {$flowid} LIMIT 1";
				// pr($sql);
		$result = $this->apps->fetch($sql);
		if(!$result) return false;
		return $result;
	
	}
	
	function updatelistevent(){
	pr($_FILES);
	pr($_POST);
		
		$brand = $this->apps->_g('brand');
		$flowid = $this->apps->_g('flowid');
		
		$schemeid = $this->apps->_p('schemeid');
		$gamesid = $this->apps->_p('gamesid'); 
		
		$submit = $this->apps->_p('submit');
		if(isset($submit)){
			$sql = "UPDATE customize_event SET `schemaid` = '{$schemeid}'									
																WHERE brand = '{$brand}' AND id='{$flowid}' "; 
			$res = $this->apps->query($sql);
			
			$sqlgames = "INSERT INTO tbl_games_references (`schemaid`, `gamesid`, `gamesnames`, `datetimes` )
						VALUES('{$schemeid}','{$gamesid}') ";
						
			// pr($sql);exit;
			if($images!=false) 	$this->apps->query("UPDATE {$CONFIG['DATABASE_WEB']}.events SET img='{$images}' WHERE id={$id}");
			return $res;
		}
		
		return false;
	}
	
	function eventimageupdate($updatelistevent=false, $images=false)
	{
	
		//update
		// pr($images); exit;
		if (!$updatelistevent) return false;
		
		$sql = "UPDATE customize_event SET files = '{$images}' WHERE id = {$updatelistevent}";
		$res = $this->apps->query($sql);
		// pr($sql);exit;
	}
	
	
}
