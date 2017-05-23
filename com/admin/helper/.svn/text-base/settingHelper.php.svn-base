<?php 

class settingHelper {
	
	function __construct($apps){
		global $logger,$CONFIG;
		
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		$this->dbshema= 'tbl';
	} 
	
	function projectList(){
		
		$start = false;
		$sql = "SELECT * FROM `tbl_brand_master`";
		$qData = $this->apps->fetch($sql,1);
		$no = 1;
		foreach($qData as $key => $val){
			$qData[$key]['no'] = $no;
			$no++;
		
		}
		return $qData;
	}
	
	function getDesign(){
		$project = intval($this->apps->_g('projects'));
		if(!$project)return false;
		$sql ="SELECT * FROM customize_templates WHERE brand = {$project} ORDER BY n_status desc,id DESC";
		 
		$qData = $this->apps->fetch($sql,1);
		foreach($qData as $key => $val){
			$qData[$key]['type']='text';			
			$qData[$key]['values']='';	
			$qData[$key]['updateon']='';			
			if($val['images']!='')	$qData[$key]['type']='upload';
			if($val['color']!='')	$qData[$key]['type']='color';
			if($val['size']!='')	$qData[$key]['type']='size';
			if($val['style']!='')	$qData[$key]['type']='style';
			
			if($val['images']!='')	$qData[$key]['values']=$val['images'];
			if($val['color']!='')	$qData[$key]['values']=$val['color'];
			if($val['size']!='')	$qData[$key]['values']=$val['size']; 
			if($val['style']!='')	$qData[$key]['values']=$val['style']; 
			if($val['textfill']!='')	$qData[$key]['values']=$val['textfill']; 
			
			if($val['images']!='')	$qData[$key]['updateon']='images';
			if($val['color']!='')	$qData[$key]['updateon']='color';
			if($val['size']!='')	$qData[$key]['updateon']='size'; 
			if($val['style']!='')	$qData[$key]['updateon']='style';  
			if($val['textfill']!='')	$qData[$key]['updateon']='textfill'; 
			
		}
		// pr($qData);
		return $qData;
	}
	
	 
	
 
	function getHapus($cid){
		global $CONFIG;
		
		if($cid){
			$sql = "delete tbl_brand_master from tbl_brand_master where id={$cid} ";
	
			//pr($sql);exit;
			$qdata  =  $this->apps->query($sql);

					if ($qdata) $data = true;
			else $data = false;
		}else {
			$data = false;	
		}
		return $data;		
	}
	
	
		function insertbrand(){
			global $CONFIG;
				//pr($_POST);
				$brand = $this->apps->_p('brand'); 
				$code = $this->apps->_p('code');   

				$submit = $this->apps->_p('submit');
	
	
					if($submit){
							$sql1 = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master (`brandname`, `codename`,`n_status`) 
									VALUES ('{$brand}', '{$code}', '1')";
						
					
							$res = $this->apps->query($sql1);
								
						}
		
		return $res;
	
		}
	
	function selectupdatebrand($id=NULL)
	{
		
		global $CONFIG;
		$sql = "select * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master where {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master.id={$id}";
		// pr($sql);
		// fetch()
		$qData = $this->apps->fetch($sql);
		return $qData;
	
	}
	
	function updatethebrand($id=NULL){
		global $CONFIG;
		//pr($_POST);
				$brand = $this->apps->_p('brand'); 
				$code = $this->apps->_p('code');   
				$submit = $this->apps->_p('submit');
				
				
	
		if(isset($submit)){
		
			$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_brand_master SET `brandname` = '{$brand}',`codename`='{$code}' WHERE id = {$id} "; 
			
		
			$res = $this->apps->query($sql);
		
			
			return $res;
		}
		
		return false;
	}
	
	 
	function updateDesignRow($images=false)
 
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
		$brand = $this->apps->_p('brand');
		$updateon = $this->apps->_p('updateon');  
		$designid = $this->apps->_p('designid');  
		$values = $this->apps->_p($updateon); 
		if($images)$values = $images;
		$sql =" SELECT brandname FROM tbl_brand_master WHERE id = {$brand} LIMTI 1";
		$projectname = $this->apps->fetch($sql);
		if($projectname) $projectname = $projectname['brandname'];
		else $projectname= "";  
		 
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `{$updateon}` = '{$values}' 
				WHERE id = {$designid} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
	
	function addDesignRow($images=false)
	{
		global $CONFIG;
		$brand = $this->apps->_p('brand');
		$updateon = $this->apps->_p('updateon');   
		$sections = $this->apps->_p('sections');  
		$values = $this->apps->_p($updateon); 
		if($images)$values = $images;
		
		$sql =" SELECT brandname FROM tbl_brand_master WHERE id = {$brand} LIMIT 1";
		$projectname = $this->apps->fetch($sql);
		if($projectname) $projectname = $projectname['brandname'];
		else $projectname= "";  
		   
			$sql = "INSERT INTO customize_templates (`brand`, `brandname`, `sections`, `{$updateon}`,n_status) 
					VALUES ('{$brand}', '{$projectname}', '{$sections}', '{$values}', 1 )";
			// pr($sql);exit;
			$res = $this->apps->query($sql);
			$lastID = $this->apps->getLastInsertId();
			 if($lastID>0){
				return true;
			}
			return false;
	}
	
	 
	function unplublishDesign(){
		global $CONFIG;
		$designid = intval($this->apps->_p('designid'));  
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `n_status` = '0' 
				WHERE id = {$designid} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 	function plublishDesign(){
		global $CONFIG;
		$designid = intval($this->apps->_p('designid'));  
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates SET `n_status` = '1' 
				WHERE id = {$designid} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 
	 function trashDesign(){
		global $CONFIG;
		$designid = intval($this->apps->_p('designid'));  
		$sql = "DELETE FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_templates  
				WHERE id = {$designid} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 
	function getEvent($child=false){
		$project = intval($this->apps->_g('projects'));
		if(!$project)return false;
		global $CONFIG;
		
		$sql = "  SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE parentid = 0 AND brand={$project} AND n_status = 1 GROUP BY id ";
		  
		$qData = $this->apps->fetch($sql,1);
		if($child){
		$newdata = array();
			if($qData){
				$n=0;
				foreach($qData as $key => $val){ 
					$childevent = $this->getChildEvent($val['id'],$val['brand']);
					if($childevent){
					 
								$qData[$key]['submenu'] = $childevent;
						 
					}
					 
				} 
			}
		}
	 // pr($qData);
		return $qData;
		
	}
 
	 function getChildEvent($parentid=false,$brand=false){
	  global $CONFIG;
		if($brand==false) return array();
		if($parentid==false) return array();
		$sql = "  SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event WHERE parentid = {$parentid} AND parentid<>0 AND brand={$brand} GROUP BY id";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return array();
		
	}
	
		
	function getAppsID($schemaid=false){
	 global $CONFIG;
		if($schemaid==false)  $qSchemaID = "";
		else $qSchemaID = " AND schemaid = '{$schemaid}' ";
		
		$sql = "  SELECT * FROM {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_apps_references WHERE 1 {$qSchemaID} AND n_status= 1  ";
		$qData = $this->apps->fetch($sql,1);
		if($qData) return $qData;
		else return array();
		
	}
	
		
	function addNewAppsID($schemaid=false){
		global $CONFIG;
		$data['result'] = false;
		$data['data'] = array(); 
		$schemaid = $this->apps->_p('schemaid');
		// $appsid= $this->apps->_p('appsid');
		// $appsid= $this->apps->_p('appsid');
		$appsname= $this->apps->_p('appsname');
		$datetimes = date("Y-m-d H:i:s");
		$appsid = md5($schemaid.$appsname);
		$sql = "  
		INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.tbl_apps_references  ( `schemaid`, `appsid`, `appsnames`, `datetimes`, `usercreator`, `n_status`) 
		VALUES ( '{$schemaid}', '{$appsid}', '{$appsname}', '{$datetimes}', '{$this->uid}', '1')
		ON DUPLICATE KEY UPDATE 
		appsid= VALUES(appsid),
		datetimes= VALUES(datetimes),
		usercreator= VALUES(usercreator),
		appsnames= VALUES(appsnames)
		";
		$qData = $this->apps->query($sql);
		$lastID = $this->apps->getLastInsertId();
		 if($lastID>0){
			$data['result'] = true;
			$data['data'] = array('appsid'=>$appsid); 
		}
		return $data;
		
	}
	
	
	function updateEventRow($images=false) 
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
		$brand = $this->apps->_p('brand');
		$name = $this->apps->_p('name');  
		$schemaid = $this->apps->_p('schemaid');  
	 
		$designid = $this->apps->_p('designid');  
		$parentid = $this->apps->_p('parentid');  
		
		 
		$qUpdate = "";
		if($name){
			$qUpdate = "  brand={$brand},  name='{$name}'  ";
		}
		if($schemaid){
			$qUpdate = "  brand={$brand},  name='{$name}', schemaid='{$schemaid}' ";
		}
		if($images){
			$files = $images;
			$fullurlpath = $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$name}/{$files}";
			$qUpdate = " brand={$brand}, name='{$name}', url='{$fullurlpath}' ,files='{$files}'  ";
		}
		if(!$qUpdate) return false;
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event SET {$qUpdate}
				WHERE id = {$designid} AND parentid={$parentid} LIMIT 1";
		 // pr($sql);
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
	
	function addEventRow($images=false) 
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
		$brand = $this->apps->_p('brand');
		$name = $this->apps->_p('name');  
		$schemaid = $this->apps->_p('schemaid');   
		$parentid = $this->apps->_p('parentid');  
		
		 
	 $files = '';
	 $fullurlpath = '';
		
		if($images){
			$files = $images;
			$fullurlpath = $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$name}/{$files}";
			 
		}
	 
		$sql = "INSERT INTO {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event
				(`id`, `parentid`, `pagestype`, `brand`, `otherid`, `name`, `schemaid`, `url`, `files`, `n_status`) 
				VALUES 
				(NULL, '{$parentid}', '1', '{$brand}', '0', '{$name}', '{$schemaid}', '{$fullurlpath}', '{$files}', '1') ";
		 // pr($sql);
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
	
	
	
	function unplublishEvent(){
		global $CONFIG;
		$designid = intval($this->apps->_p('designid'));  
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event SET `n_status` = '0' 
				WHERE id = {$designid} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 	function plublishEvent(){
		global $CONFIG;
		$designid = intval($this->apps->_p('designid'));  
		$sql = "UPDATE {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event SET `n_status` = '1' 
				WHERE id = {$designid} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}
 
	 function trashEvent(){
		global $CONFIG;
		$designid = intval($this->apps->_p('designid'));  
		$sql = "DELETE FROM {$CONFIG['DATABASE'][0]['DATABASE']}.customize_event  
				WHERE id = {$designid} LIMIT 1";
		 
		$res = $this->apps->query($sql); 
		if($res){
			return true;
		}
		return false;
	}

}
?>