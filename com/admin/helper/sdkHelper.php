<?php 

class sdkHelper {
	
	function __construct($apps){
		global $logger,$CONFIG;
		
		$this->logger = $logger;
		$this->apps = $apps;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		$this->dbshema= 'tbl';
	}
	
	function sdklisttemplate(){
		
		$start = false;
		$sql = "SELECT id, brand, brandname, n_status FROM `customize_templates` WHERE 1 GROUP BY brandname";
		$qData = $this->apps->fetch($sql,1);
		$no = 1;
		foreach($qData as $key => $val){
			$qData[$key]['no'] = $no;
			$no++;
		
		}
		return $qData;
	}
	
	function getTemplates(){
		$brand = intval($this->apps->_g('brand'));
		if(!$brand)return false;
		$sql ="SELECT * FROM customize_templates WHERE brand = {$brand}";
		$qData = $this->apps->fetch($sql,1);
		foreach($qData as $key => $val){
			$qData[$key]['type']='text';			
			$qData[$key]['values']='';			
			if($val['images']!='')	$qData[$key]['type']='upload';
			if($val['images']!='')	$qData[$key]['values']=$val['images'];
			if($val['color']!='')	$qData[$key]['values']=$val['color'];
			if($val['size']!='')	$qData[$key]['values']=$val['size']; 
			if($val['style']!='')	$qData[$key]['values']=$val['style']; 
			
			if($val['images']!='')	$qData[$key]['updateon']='images';
			if($val['color']!='')	$qData[$key]['updateon']='color';
			if($val['size']!='')	$qData[$key]['updateon']='size'; 
			if($val['style']!='')	$qData[$key]['updateon']='style'; 
			
		}
		
		return $qData;
	}
	
	function updateImage($val=array())
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
		
		$image = $val['filename'];
		$sections = $val['sections'];
		$brand = $val['brand'];
		$brandname = $val['brandname'];
		
		// $brand = $this->apps->_p('brand');
		// if($brand==4) $brandname = 'amild';
		// else $brandname = 'marlboro';
			
		if($val){
			
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.customize_templates SET `images` = '{$image}' 
					WHERE sections = '{$sections}' AND brand = {$brand} AND brandname = '{$brandname}' ";
			// pr($sql);
			$res = $this->apps->query($sql); 
			return true;
		
		}	
		
	}
	
	function insertnewsupdate()
	{
		global $CONFIG;
		// pr($_FILES);
		// pr($_POST);
		$brand = $this->apps->_p('brand');
		if($brand==4) $brandname = 'amild';
		else $brandname = 'marlboro';
		 		
		foreach($_POST as $key =>$val){
		// pr($key);
		// pr($val);
		$keys = explode("_", $key);
		if(count($keys)==2){
			
			$submit = $this->apps->_p('input');
			if(isset($submit)){
				$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.customize_templates SET `$keys[1]` = '{$val}' 
						WHERE sections = '{$keys[0]}' AND brand = {$brand} AND brandname = '{$brandname}' ";
				// pr($sql);
				$res = $this->apps->query($sql); 
				return $res;
			}
			}
			// return false;
		}
		// exit;
		return false;
	}
	
	
	function newsupdate($insertnewsupdate=false, $images=false)
	{
		global $CONFIG;
		//update
		// pr($images); exit;
		if (!$insertnewsupdate) return false;
		
		$sql = "UPDATE customize_templates SET images = '{$images}' WHERE id = {$insertnewsupdate}";
		$res = $this->apps->query($sql);
		// pr($sql);exit;
	}
	
	function selectupdatedata($id=NULL)
	{
		
		global $CONFIG;
		$sql = "SELECT * FROM {$CONFIG['DATABASE_WEB']}.events WHERE id = {$id} LIMIT 1";
		// pr($sql);
		// fetch()
		$qData = $this->apps->fetch($sql);
		return $qData;
	
	}
	
	function updatethedata($id=NULL, $images=false){
		global $CONFIG;
		// pr($_POST);exit;
		$title = $this->apps->_p('title');
		$brief = $this->apps->_p('brief');
		$content = $this->apps->_p('content');  
		$type = $this->apps->_p('type');  
		$created_date = date('Y-m-d H:i:s');
		$posteddate = $this->apps->_p('posted_date');
		$unpublished_date = $this->apps->_p('unpublished_date');
		$city = $this->apps->_p('city'); 
		$n_status = $this->apps->_p('n_status');
		
		$submit = $this->apps->_p('submit');
		if(isset($submit)){
			$sql = "UPDATE {$CONFIG['DATABASE_WEB']}.events SET `title` = '{$title}', 
																`brief` = '{$brief}',
																`content` = '{$content}', 
																`type` = '{$type}', 
																`created_date` = '{$created_date}', 
																`unpublished_date` = '{$unpublished_date}', 
																`posted_date` = '{$posteddate}', 
																`cityid` = '{$city}', 
																`n_status` = '{$n_status}'
																WHERE id = '{$id}' "; 
			$res = $this->apps->query($sql);
			// pr($sql);exit;
			if($images!=false) 	$this->apps->query("UPDATE {$CONFIG['DATABASE_WEB']}.events SET img='{$images}' WHERE id={$id}");
			return $res;
		}
		
		return false;
	}
	
	function insertnewsection(){
	
	// pr($_POST);
		
		$brand = $this->apps->_p('brand');
		if($brand==4) $brandname = 'amild';
		else $brandname = 'marlboro';
		$sections = $this->apps->_p('sections');
		$colorfield = $this->apps->_p('colorfield');
		$sizefield = $this->apps->_p('sizefield'); 
		$stylefield = $this->apps->_p('stylefield'); 
		
		$simpan = $this->apps->_p('submit');
		if(isset($simpan)){
			$sql = "INSERT INTO customize_templates (`brand`, `brandname`, `sections`, `color`, `size`, `style` , `n_status`) 
					VALUES ('{$brand}', '{$brandname}', '{$sections}', '{$colorfield}', '{$sizefield}', '{$stylefield}', 1 )";
			// pr($sql);exit;
			$res = $this->apps->query($sql);
			return $res;
		}
		// exit;
		return false;
	}
	
	function sdkimageupdate($insertnewsection=false, $images=false)
	{
	
		//update
		// pr($images); exit;
		if (!$insertnewsection) return false;
		
		$sql = "UPDATE customize_templates SET images = '{$images}' WHERE id = {$insertnewsection}";
		$res = $this->apps->query($sql);
		// pr($sql);exit;
	}
	
}
?>