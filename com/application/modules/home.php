<?php
class home extends App{
	
	
	function beforeFilter(){
	  
	
		$this->registerHelper = $this->useHelper("registerHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['BASE_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_DASHBOARD']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
		$this->assign('n_status', (string)$this->_g('n_status'));
		$this->assign('brandid', $this->_g('brandid'));
		
		// $this->assign('getCity',$loadCity);
		
		$this->assign('pages', $this->_g('page'));
		$this->assign('acts', $this->_g('act')); 
		
		
	}
	
	function main(){
			
	
		global $LOCALE,$CONFIG; 
	
		//pr($res);
		$time['time'] = '%H:%M:%S';
		$this->assign('time',$time);
		$this->assign('notiftype',intval($this->_p('notiftype')));
		$this->assign('publishedtype',intval($this->_p('publishedtype')));
	
		$this->log('surf','register');
	
		if ($this->_p('submit')){
	
	
	
			$name=strip_tags($this->_p('name'));
			$email=strip_tags($this->_p('email'));
			$alamat=strip_tags($this->_p('alamat'));
			
			//echo $name;exit;
			if (ctype_alpha(str_replace(' ', '', $name))==false||$name=='NAME'||$name=='')
			{
				$gros=array('msgn'=>'Invalid Name','email'=>$email,'alamat'=>$alamat);;
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/dashboard.html');
			}
			
	//echo $this->_p('email');exit;
			$emailval=$this->is_valid_email(strip_tags($this->_p('email')));
			if(!$emailval)
			{
				$gros=array('email_ngga_valid'=>'Invalid Email','name'=>$name,'alamat'=>$alamat);;
				$this->assign('status',$gros);
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/dashboard.html');
			}
			
		
			
			
			if (!ctype_alpha(str_replace(' ', '', $alamat)))
			{
				$gros=array('alamat_ngga_valid'=>'Invalid City','name'=>$name,'alamat'=>$alamat);;
				$this->assign('status',$gros);
				
				return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/dashboard.html');
			}
			
			// $checkEmailExist = $this->registerHelper->checkEmailExist(strip_tags($this->_p('email')));
			// var_dump($checkEmailExist);exit;
			// if($checkEmailExist){
				// $this->assign('check_mail',"Oops, something wasn't right. Your email has been registered.");
				// return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/dashboard.html');
			// }
//pr($_POST);exit;
			// pr($files);exit;
			$insertnewdata = $this->registerHelper->insertnewdata();
			
			
				if($insertnewdata==true){		
					$this->registerHelper->sendGlobalMail($insertnewdata);
					sendRedirect($CONFIG['BASE_DOMAIN']."home");
				}else{
					$this->assign('alamat_ngga_valid',"Oops, something wasn't right. Please make sure all fields are filled in correctly.");
					return $this->View->toString(TEMPLATE_DOMAIN_WEB .'/apps/dashboard.html');
				}
										
		}
		
		
		$this->assign('user', $this->user);
		return $this->View->toString(TEMPLATE_DOMAIN_WEB .'apps/dashboard.html');
		
	}
	function loadChart()
	{
		
		$result = $this->reportHelper->datatouchbaseHome();
		
		print json_encode($result);die;
	
	}
	function callsheader($file='download-data'){
		 
		
		$filename = $file.date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); 
		print $this->View->toString(TEMPLATE_DOMAIN_WEB .'widgets/downloaddata.html');
		exit;
	
	}
	
	function ajaxPaging()
	{
		
		$start = intval($this->_g('start'));
		
		if ($this->_g('ajax')){
			$ajax = $this->reportHelper->dataphotoentourage($start);
		}
		// pr($ajax);
		if ($ajax){ 
			print json_encode(array('status'=>true, 'data'=>$ajax));
		}else{ 
			print json_encode(array('status'=>false));
		}
		
		exit;
	}
}
?>