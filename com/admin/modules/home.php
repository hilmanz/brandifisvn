<?php
class home extends App{
	
	
	function beforeFilter(){
	  
		$this->reportHelper = $this->useHelper("reportHelper");
		
		global $LOCALE,$CONFIG;
		$this->assign('basedomain', $CONFIG['ADMIN_DOMAIN']);
		$this->assign('basedomainpath', $CONFIG['BASE_DOMAIN_PATH']);
		$this->assign('assets_domain', $CONFIG['ASSETS_DOMAIN_ADMIN']);
				
		$this->assign('locale', $LOCALE[1]);
		$this->assign('startdate', $this->_g('startdate'));
		$this->assign('enddate', $this->_g('enddate'));
		$this->assign('n_status', (string)$this->_g('n_status'));
		$this->assign('brandid', $this->_g('brandid'));
		$this->assign('badetail',$this->reportHelper->getsba());
		$this->assign('branddetail',$this->reportHelper->getBrand());
		
		$loadCity = $this->reportHelper->loadCity();		
		$this->assign('getCity',$loadCity);
		
		$this->assign('pages', $this->_g('page'));
		$this->assign('acts', $this->_g('act')); 
		
		
	}
	
	function main(){
			$this->assign('uid',strip_tags($this->_g('uid')));
			$this->assign('areaid',strip_tags($this->_g('areaid')));
			$this->assign('startdate',strip_tags($this->_g('startdate')));
			$this->assign('enddate',strip_tags($this->_g('enddate')));
			$limitstatus = true;
				if(strip_tags($this->_g('download'))=='report'){
						$limitstatus = false;
				}
				
				$enturagereport =  $this->reportHelper->dataphotoentourage(null,20,$limitstatus);
				// pr($enturagereport);
				$this->assign('total',intval($enturagereport['total']));
				$this->assign('enturagereport',$enturagereport['result']);
			 
				
				$headerReport =  $this->reportHelper->headerReport();		
// pr($headerReport);				
				$this->assign('headerReport', $headerReport);  	
				// $this->assign('new_registrant_count', $headerReport['new']);  	
 				// $this->assign('recontact_count', $headerReport['existing']);  
 				// $this->assign('rejectedregistrant', $headerReport['reject']);  
 				// $this->assign('pendingregistrant', $headerReport['pending']); 
				
				// $allRegis_count['total'] = intval($headerReport['new']['total'])+intval($headerReport['existing']['total'])+intval($headerReport['pending']['total']);
 				// $this->assign('allRegis_count', $allRegis_count); 
				
			if(strip_tags($this->_g('download'))=='report'){

				$this->callsheader();

			}
					
		
		return $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'apps/entourage-report.html');
		
	}
	
	function callsheader($file='download-data'){
		 
		
		$filename = $file.date('Ymd_gia').".xls";
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=$filename");  
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false); 
		print $this->View->toString(TEMPLATE_DOMAIN_ADMIN .'widgets/downloaddata.html');
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