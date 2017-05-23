<?php 
class settings extends App{

	function beforeFilter(){
		global $LOCALE,$CONFIG;
		
		$this->settinghelper = $this->useHelper('settinghelper');
		$this->uploadHelper = $this->useHelper('uploadHelper');
		
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
		$this->assign('locale', $LOCALE[1]);		
		$this->assign('pages', strip_tags($this->_g('page')));
		$this->assign('projects', intval($this->_g('projects')));
		$this->assign('user',$this->user);
	}
	
	function main(){
	
		$projectList = $this->settinghelper->projectList();
		// pr($sdklisttemplate);
		$this->assign('projectList',$projectList);
		
		$this->log('surf','registrant_management');
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'/apps/project-list.html');
		 
	}
		
	function designs()
	{
		global $CONFIG; 
	 
		$datatemplate= $this->settinghelper->getDesign();
  
		$this->assign('datacustomize',$datatemplate);
		 
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/template-generator.html');
	 
	}
	
	function savedesign()
	{
		global $CONFIG; 
	 
		$data['result'] = false;
		$data['message'] = "halt!!";
		$data['filename']='';
			$images = array();
			$filenames = false;
			$brand = $this->_p('brand');
			$sections = $this->_p('sections');
		 
		
			// $path = $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$sections}/";
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/{$sections}/";
			
			if(isset($_FILES['images'])&&$_FILES['images']['name']!=NULL) {
				if (isset($_FILES['images'])&&$_FILES['images']['size'] <= 20000000) {
						  
						$images = $this->uploadHelper->uploadThisImage($_FILES['images'],$path);
					 
						
						$filenames= $images['arrImage']['filename'];
						$data['filename']= $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$sections}/{$images['arrImage']['filename']}";
										
				}
			}
			
			// exit;
			
			$result = $this->settinghelper->updateDesignRow($filenames);
			if($result){
				$data['result'] = true;
				$data['message'] = 'success';
			}
		 
		 
		
		echo json_encode($data);
		exit;
	 
	}

 
	
	function addRowDesign(){
	 
	global $CONFIG;
		$data['result'] = false;
		$data['message'] = "halt!!";
		$data['filename']='';
			$images = array();
			$filenames = false;
			$brand = $this->_p('brand');
			$sections = $this->_p('sections');
		 
		
			// $path = $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$sections}/";
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/{$sections}/";
			
			if(isset($_FILES['images'])&&$_FILES['images']['name']!=NULL) {
				if (isset($_FILES['images'])&&$_FILES['images']['size'] <= 20000000) {
						  
						$images = $this->uploadHelper->uploadThisImage($_FILES['images'],$path);
					 
						
						$filenames= $images['arrImage']['filename'];
						$data['filename']= $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$sections}/{$images['arrImage']['filename']}";
										
				}
			}
			
			// exit;
			
			$result = $this->settinghelper->addDesignRow($filenames);
			if($result){
				$data['result'] = true;
				$data['message'] = 'success';
			}
		 
		echo json_encode($data);
		exit;
	
	}
	
	
	function clearDesign(){
	$data['result'] = false;
		$data['message'] = "halt!!";
		$result = $this->settinghelper->unplublishDesign();
		if($result){
				$data['result'] = true;
				$data['message'] = 'success delete design';
			}
		 
		echo json_encode($data);
		exit;
		
	}
	function activateDesign(){
	$data['result'] = false;
		$data['message'] = "halt!!";
		$result = $this->settinghelper->plublishDesign();
		if($result){
				$data['result'] = true;
				$data['message'] = 'success activated design';
			}
		 
		echo json_encode($data);
		exit;
		
	}
	
	function trashDesign(){
	$data['result'] = false;
		$data['message'] = "halt!!";
		$result = $this->settinghelper->trashDesign();
	
		if($result){
				$data['result'] = true;
				$data['message'] = 'success trashing design';
			}
		 
		echo json_encode($data);
		exit;
		
	}
	
	
	function event(){
			$result = $this->settinghelper->getEvent(true);
			
			 	$dataapps = $this->settinghelper->getAppsID();
				 
			$this->assign('datacustomize',$result); 
			$this->assign('dataapps',$dataapps); 
			return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/event-generator.html');
	}
	
	function getAppsID(){
		$data['result'] = false;
		$data['data'] = array();
		$result = $this->settinghelper->getAppsID();
				if($result){
					$data['result'] = true;
					$data['data'] = $result;
				}
		 print json_encode($data);
		 exit;
	}
	
	function addNewAppsID(){
		$data['result'] = false;
		 $data['data'] = array();
		$result = $this->settinghelper->addNewAppsID();
				if($result['result']){
					$data['result'] = $result['result']; 
					$data['data'] = $result['data'];
				}
		 print json_encode($data);
		 exit;
	}
	
	
	function saveevent()
	{
		global $CONFIG; 
	 
		$data['result'] = false;
		$data['message'] = "halt!!";
		$data['filename']='';
			$images = array();
			$filenames = false; 
			$sections = $this->_p('name');
		 
		
			// $path = $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$sections}/";
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/{$sections}/";
			
			if(isset($_FILES['url'])&&$_FILES['url']['name']!=NULL) {
				if (isset($_FILES['url'])&&$_FILES['url']['size'] <= 20000000) {
						  
						$images = $this->uploadHelper->uploadThisImage($_FILES['url'],$path);
					 
						
						$filenames= $images['arrImage']['filename'];
						$data['filename']= $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$sections}/{$images['arrImage']['filename']}";
										
				}
			}
			
			// exit;
			
			$result = $this->settinghelper->updateEventRow($filenames);
			if($result){
				$data['result'] = true;
				$data['message'] = 'success';
			}
		 
		 
		
		echo json_encode($data);
		exit;
	 
	}
	
	function addRowEvent()
	{
		global $CONFIG; 
	 
		$data['result'] = false;
		$data['message'] = "halt!!";
		$data['filename']='';
			$images = array();
			$filenames = false; 
			$sections = $this->_p('name');
		 
		
			// $path = $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$sections}/";
			
			$path = $CONFIG['LOCAL_PUBLIC_ASSET']."content/{$sections}/";
			
			if(isset($_FILES['url'])&&$_FILES['url']['name']!=NULL) {
				if (isset($_FILES['url'])&&$_FILES['url']['size'] <= 20000000) {
						  
						$images = $this->uploadHelper->uploadThisImage($_FILES['url'],$path);
					 
						
						$filenames= $images['arrImage']['filename'];
						$data['filename']= $CONFIG['BASE_DOMAIN_PATH']."public_html/public_assets/content/{$sections}/{$images['arrImage']['filename']}";
										
				}
			}
			
			// exit;
			
			$result = $this->settinghelper->addEventRow($filenames);
			if($result){
				$data['result'] = true;
				$data['message'] = 'success';
			}
		 
		 
		
		echo json_encode($data);
		exit;
	 
	}
	
	function clearEvent(){
	$data['result'] = false;
		$data['message'] = "halt!!";
		$result = $this->settinghelper->unplublishEvent();
		if($result){
				$data['result'] = true;
				$data['message'] = 'success delete event';
			}
		 
		echo json_encode($data);
		exit;
		
	}
	function activateEvent(){
	$data['result'] = false;
		$data['message'] = "halt!!";
		$result = $this->settinghelper->plublishEvent();
		if($result){
				$data['result'] = true;
				$data['message'] = 'success activated event';
			}
		 
		echo json_encode($data);
		exit;
		
	}
	
	function trashEvent(){
		$data['result'] = false;
		$data['message'] = "halt!!";
		$result = $this->settinghelper->trashEvent();
	
		if($result){
				$data['result'] = true;
				$data['message'] = 'success trashing event';
			}
		 
		echo json_encode($data);
		exit;
		
	}
	
	
}
?>