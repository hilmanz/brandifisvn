<?php 

class appsHelper {

	function __construct($apps){
	
	
		global $logger,$CONFIG;
		$this->logger = $logger;
		$this->config = $CONFIG;
		$this->apps = $apps;
		$this->uid  = 0;
		if(is_object($this->apps->user)) $this->uid = intval($this->apps->user->id);
		
		$this->dbshema = "touchbase";	
		
		$this->week = 7;
		$week = intval($this->apps->_request('weeks'));
		if($week!=0) $this->week = $week;
		
		$this->startweekcampaign = "2013-05-20";
		$this->datetimes = date("Y-m-d H:i:s");
 
		
	   
	}
	
	
	function getLastOldToken(){
	
	 
		
		$sql = " SELECT token FROM my_games WHERE userid = {$this->uid}  ORDER BY datetimes DESC LIMIT 1";
		
		$lastToken = $this->apps->fetch($sql);
		if($lastToken){
			//fresh start
				// pr($sql);
				// pr($lastToken);
			if($lastToken['token']==''){
				return $this->uid;
			}else{
			// has token old
				return $lastToken['token'];
			}
			
		}
			
		return false;	
		
	}
	
	function checkappsscheme($appsid=false){
		if(!$appsid) return false;
		$sql = "SELECT id FROM tbl_apps_references WHERE appsid='{$appsid}' LIMIT 1  ";
		$qData = $this->apps->fetch($sql);
		$this->logger->log($sql);
		if($qData) return intval($qData['id']);
		return false;
	}
	
	function checkstatus()
	{	
		
		$checkCode = false;
		$mytoken = false;

		/* parse win status of user games */
		$token = strip_tags($this->apps->_p('token'));
		$win = strip_tags($this->apps->_p('win'));
		$userid = strip_tags($this->apps->_p('userid'));
				
		$appsid = strip_tags($this->apps->_p('appsid'));
		$this->logger->log("games hash id = {$appsid} ");
		$appsid = $this->checkappsscheme($appsid);
		if(!in_array($appsid,$this->gamesarrayid)) return false;
		$level = intval($this->apps->_p('level'));
		
		$point = intval($this->pointarr[$level]);
		
		if(!in_array($point,$this->pointarr)) return false;
		
		$getLastOldToken = $this->getLastOldToken(); 
		$salt = "gameapihelper";
			$this->logger->log('phase 1: check token '.$appsid );
			$this->logger->log('phase 1b: check level '.$level );
		if(!$token) return false;
			$this->logger->log('phase 1: OK ');
		/* token matching with erwin */
		$mytoken = sha1($this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		$mytokentolerance = sha1($this->uid.date("YmdHi",strtotime(date("YmdHi")." -1 minute ")).$getLastOldToken."true{".$salt."}"); /* tolerance 1 minute */
			$this->logger->log('phase 2: check param information ');
		if($this->uid==0) return false;
			$this->logger->log('phase 2: id OK ');
		if($this->uid!=$userid) return false;
			$this->logger->log('phase 2: all OK ');
		/* check user dont have code in publicity code where code not exists in their inventory */
		$checkuserplaygames = $this->checkuserplaygames();
		$checkuserwinthislevel = $this->checkuserwinthislevel();
		
		
		
			$this->logger->log('phase 2b: token '.$token.' '.$mytoken.', tolerance: '.$mytokentolerance.' using token concat this = '.$this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		// if($token!=$mytoken) {
			// if($token!=$mytokentolerance) return false;
		// }
		
		/* give user log playing games */
			$this->logger->log('phase 3: log user current games ');
			$playingappsid = intval($this->playinggames($mytoken));
			$this->logger->log('phase 3: OK ');
			
			$this->logger->log('phase 2b: token OK ');
		
			$this->logger->log('phase 4: check win ');
		if($win!="true") return false;
			$this->logger->log('phase 4: OK ');
		
			$this->logger->log('phase 5: check user play games ');
		if(!$checkuserplaygames) return false;
			$this->logger->log('phase 5: OK ');
		
		$this->logger->log('phase 6: check code public for games in inventory');
		$checkCode = $this->checkpublicexistsinventory();
		if(!$checkCode) return false;
			$this->logger->log('phase 6: OK ');
		
		if($checkCode['result']){
					$this->logger->log('phase 6: result OK ');
					/* save to inventory user if win */
					$this->logger->log('phase 7: save to inventory ');
				if($win=="true"){
					$this->logger->log('phase 7: OK ');
					if(!$checkuserwinthislevel)  return false;
					
					if(in_array($level,$this->level)) {
						$checkuserplaygames = $this->checkuserplaygames();
						if(!$checkuserplaygames) return false; 
						
						$saved = $this->savetoinventory($win,$checkCode['data'],$playingappsid);
					}else $saved = true;
					if($saved) return $checkCode['data'];
				}
				
		}else{
				$this->logger->log('phase 6: result NOT OK ');
			$checkCode = false;
			/* if not found code in publicity code, create 1 code for this user */
				$this->logger->log('phase 7b: generate code ');
			$firstcreatecode = $this->generateCode();
			if(!$firstcreatecode) return false;		
				$this->logger->log('phase 7b: OK ');
			
				$this->logger->log('phase 8: check code in this inventory again ');
			$checkCode = $this->checkpublicexistsinventory();
			if(!$checkCode) return false;
				$this->logger->log('phase 8: OK ');
			if($checkCode['result']){
					$this->logger->log('phase 8: result OK ');
					/* save to inventory user if win */
				if($win=="true"){
					$this->logger->log('phase 9: save to inventory ');
					if(!$checkuserwinthislevel) return false;
					
					if(in_array($level,$this->level)) {	
						$checkuserplaygames = $this->checkuserplaygames();
						if(!$checkuserplaygames) return false;
						 						
						$saved = $this->savetoinventory($win,$checkCode['data'],$playingappsid);
					}else $saved = true;
					$this->logger->log('phase 9: '.$saved);
					if($saved) return $checkCode['data'];
				}
			}else return false;
		}
		
		return false;
	}
	
 
	function uploadContents($token=false,$images=false,$imagesname=false){
		global $CONFIG;
		
		$path = $CONFIG['LOCAL_PUBLIC_ASSET']."contests/";
		$datetime = date("Y-m-d H:i:s");
		$data['status'] = false;
		$data['code'] = 0;
		$data['msg'] ="cannot save data";
		$token = strip_tags($this->apps->_p('token'));
		$appsid = strip_tags($this->apps->_p('appsid'));
		if(!$appsid)  {
			$data['msg'] ="apps not found  ";
			$this->logger->log(json_encode($data));
			return  $data;
		}
		$appsid = $this->checkappsscheme($appsid);
		if(!$appsid)  {
			$data['msg'] ="apps id invalid ";
				$this->logger->log(json_encode($data));
			return  $data;
		}
		$multiplayer = intval($this->apps->_p('multiplayer')); 
		$userid = $this->uid;
		$registrantmail = strip_tags($this->apps->_p('registrantmail')); 
		$dstmail = @$this->apps->user->email;
		$win = intval($this->apps->_p('win'));
		$playtime = strip_tags($this->apps->_p('uploaddate'));
		if(!$playtime)$playtime = $datetime;  
		$point = 0;
		$getLastOldToken = $this->getLastOldToken();
		
		$salt = "gameapihelper";
		 
		// if(!$token)  {
			// $data['msg'] =" token not found";
			// return  $data;
		// }
	 
		/* token matching    */
		$mytoken = sha1($this->uid.date("YmdHi").$getLastOldToken."true{".$salt."}");
		$mytokentolerance = sha1($this->uid.date("YmdHi",strtotime(date("YmdHi")." -1 minute ")).$getLastOldToken."true{".$salt."}"); /* tolerance 1 minute */ 
		$sql ="SELECT name,ownerid FROM registrant_data WHERE email = '{$registrantmail}'   LIMIT 1 ";
		$cansendmail = false;
		$qData = $this->apps->fetch($sql);	
		 	
		$this->logger->log($sql);
		if(!$this->uid)  {
			if($qData){
				$userid = $qData['ownerid'];
			}else{
				$data['msg'] ="  user data not found ";
				return  $data;
			}
		}
		 
		$qImages = "";
		$data['mailstatus']['result'] = false;
		if($images) {
			 
			$name = "";
			if($qData){
				$cansendmail = true;
				$name = $qData['name'];
			}
			
			 
					$attachment['filerealpath']=$path.$images;
					$messages ="
					
						<p>Hi, {$name}</p>
						<br/> 
						<p>Thank you for playing the Face Maker. We hope you enjoy your personal portrait of Decisiveness.</p>
						<p>And always remember:<b> GO GIRLS - MODEL .</b> </p>
						<br/>
						<br/>
						<p>Regards,</p>
						<br/>
						<p>Your friends at Go Girls</p>
					
					
					"; 
					if($cansendmail){
						$subject = "Go girls Photo";
						$from['from'] = "noreply@gogirls-noreply.com";
						$from['alias'] = "Go Girls App";
						// $data['mailstatus']=$this->apps->newsHelper->sendGlobalMail($registrantmail,$from,$messages,$subject,false,$attachment);
					
					}else {
						$data['mailstatus']['result'] = false;
					}
				$qImages = ",images=VALUES(images),imagesname=VALUES(imagesname)";
		}
	 
		if($data['mailstatus']['result'])	{
		
			$mailsend = 1;
		}else{
			$mailsend = 0;
		}
		
		$sql = " INSERT INTO my_games 
		( 	gamesid ,	userid 	,point 	,datetimes, 	n_status ,token,win,dstmail,registrantmail,images,multiplayer,sendmail,imagesname) 
		VALUES ('{$appsid}',{$userid},{$point},'{$playtime}',1,'{$token}',{$win},'{$dstmail}','{$registrantmail}','{$images}','{$multiplayer}','{$mailsend}','{$imagesname}')
		ON DUPLICATE KEY UPDATE point=point+1,sendmail=sendmail+{$mailsend}{$qImages}
		";	 
		$this->logger->log($sql);
		  // pr($sql);
		if($this->apps->query($sql)){ 
			$data['status'] = true;
			$data['code'] = 1;
			$data['msg'] ="data saved";
			return $data;
		}else return  $data;
		
	
	}
	
	function checkdataimages($imagesname=false){
		if($imagesname){
			$sql =" SELECT COUNT(1) total FROM my_games WHERE imagesname='{$imagesname}' LIMIT 1";
			$qData = $this->apps->fetch($sql);
			// pr($sql);
			// pr($qData);
			if($qData){
				if($qData['total']>0) return false;
			}
			return true;
		}
		return false;
	}
	
	function checkuserwinthislevel(){
	
		$datetime = date("Y-m-d H:i:s");
		
		$appsid = intval($this->apps->_p('appsid'));
			$appsid = $this->checkappsscheme($appsid);
		if(!in_array($appsid,$this->gamesarrayid)) return false;
		
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		//check user has win the game at this level
		$sql = " 
		SELECT COUNT(*) total 
		FROM my_games 
		WHERE 
		appsid={$appsid}  
		AND point={$point} 
		AND win=1 
		AND userid={$this->uid} 
		AND DATE(datetimes)=DATE('{$datetime}') 
		LIMIT 1 ";
		
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if(!array_key_exists('total',$qData)) return false;
		if (in_array($appsid,$this->singlelevelgame)){
			if($qData['total']<1) return true;
		}else{
			if($qData['total']<3) return true;
			// if($qData['total']<=14) return true; /* changes 3 times per games point*/
		}
		
		return false;
	}
	
	
	function checkuserplaygames(){
	
		$datetime = date("Y-m-d H:i:s");
		
		$appsid = strip_tags($this->apps->_p('appsid'));
			$appsid = $this->checkappsscheme($appsid);
		if(!in_array($appsid,$this->gamesarrayid)) return false;
		
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		$sql ="
			SELECT COUNT(*) total 
			FROM my_badges b
			LEFT JOIN badges_source_type t ON t.id = b.sourceType
			WHERE 
			userid={$this->uid} 
			AND DATE(redeem_date)=DATE('{$datetime}')  
			AND t.name = 'games {$appsid}' 
			LIMIT 1";
		
		$this->logger->log($sql);
		$qData = $this->apps->fetch($sql);
		if(!$qData) return false;
		if(!array_key_exists('total',$qData)) return false;
		if (!in_array($appsid,$this->singlelevelgame)){
			if($qData['total']<3) return true;
 
		}else{
			if($qData['total']<1) return true;
		}
		
		return false;
	}
	
	 
	
	function checkuserplayingthislevel(){
		
		$datetime = date("Y-m-d H:i:s");
		$appsid = strip_tags($this->apps->_p('appsid'));
			$appsid = $this->checkappsscheme($appsid);
		$level = intval($this->apps->_p('level'));
		$point = intval($this->pointarr[$level]);
		if(!in_array($point,$this->pointarr)) return false;
		
		$sql ="SELECT win FROM my_games WHERE DATE(datetimes)=DATE('{$datetime}') AND userid={$this->uid} AND appsid={$appsid} AND point={$point} AND win=1 LIMIT 1";
		$this->logger->log(" check level games : ".$sql);
		$data = $this->apps->fetch($sql);
		$this->logger->log(" check level games result : ".json_encode($data));
		if($data)  return false;
		else return true;
	}
	
	function savetoinventory($win=false,$code=false,$playingappsid=0){	
		
		if(!$win) return false;
		if(!$code) return false;
		if($playingappsid==0) return false;
		if(!$this->checkuserplayingthislevel()) return false;
		
		$point = 1;
		$appsid = strip_tags($this->apps->_p('appsid'));
			$appsid = $this->checkappsscheme($appsid);
		$level = intval($this->apps->_p('level'));
		$gamepoint = intval($this->pointarr[$level]);
		if(!in_array($gamepoint,$this->pointarr)) return false;
				 
		$win = strip_tags($this->apps->_p('win'));
		$this->logger->log(" win : ".$win);
		if($win!="true") return false;
		$this->logger->log($appsid);
		if(!in_array($appsid,$this->gamesarrayid)) return false;
		$appsnames =$appsid;
		 /* userid 	codeid 	codepublicityid 	n_status 	histories */
		$sql = "SELECT id FROM badges_source_type WHERE name = 'games {$appsid}' LIMIT 1";
		$sourceType = $this->apps->fetch($sql);
		if(!$sourceType) return false;
		$datetimes = date("Y-m-d H:i:s");
		
		$sql = " INSERT INTO my_badges 
		(userid, badgesid, codeid, n_status, sourceType,redeem_date) 
		VALUES ({$this->uid},{$code['badgesid']},{$code['id']},1,'{$sourceType['id']}','{$datetimes}')";	
		
		$this->logger->log($sql);
		
		$this->apps->query($sql);
		if($this->apps->getLastInsertId()){
			
			$sql = " UPDATE my_games SET win  = 1 WHERE id = {$playingappsid} AND point = {$gamepoint} AND appsid ={$appsid} LIMIT 1";
			$this->apps->query($sql);
			
			
			return true;
		}else return false;
		return false;
	}
	
	function checkpublicexistsinventory(){
		
		
		$data['result'] = false;
		$data['data'] = false;
	
		$appsid = strip_tags($this->apps->_p('appsid'));
			$appsid = $this->checkappsscheme($appsid);
		if(!in_array($appsid,$this->gamesarrayid)) return false;
		
		$sql ="
			SELECT * FROM  badges_code public
			WHERE NOT EXISTS 
			(SELECT * FROM my_badges WHERE codeid = public.id AND userid={$this->uid} ) 
			AND code_channel='games' 
			AND code_sub_channel='games_{$appsid}' 
			AND n_status = 1 
			LIMIT 1";
		
		$qData = $this->apps->fetch($sql);
					
		if($qData) {
			/* randcode , add proba in here if want to use of fontend */
			$masterbBadges = $this->apps->badgeHelper->masterbBadges();
			$randcodeidmekans = $this->apps->badgeHelper->randomcodegen($masterbBadges);
			$this->logger->log("before : ");
			if($randcodeidmekans!=false) $qData['badgesid']=$randcodeidmekans;
			$this->logger->log("after : ".$qData['badgesid']);
			$data['result'] = true;
			$data['data'] = $qData;
		
		}
		return $data;
		
	
	}
	
	function generateCode()
	{
	
		$appsid = strip_tags($this->apps->_p('appsid'));
			$appsid = $this->checkappsscheme($appsid);
		if(!in_array($appsid,$this->gamesarrayid)) return false;
		$location = 'GAMES CODES';
		$channel = "games";
		$subchannel = "games_{$appsid}";
		 
	 	$iscommonbadges=1;
		$datetime = date("Y-m-d H:i:s");
		$getres = false;		
		
	 
		 
			$letters  = "ABCDEFGHJKMNPQRSTUVWXYZ23456789";
			$maskcode = substr(str_shuffle($letters), 0, 10);
			

			$sql = "INSERT INTO {$this->config['DATABASE_WEB']}.badges_code 
					(code, code_type, code_sub_channel, code_channel, created_date,  n_status)
					VALUES 
					('{$maskcode}', {$iscommonbadges}, '{$subchannel}', '{$channel}',   '{$datetime}', 1 )";
			// pr($sql);
			 
			 $this->apps->query($sql);
			if($this->apps->getLastInsertId()){
				$getres[$maskcode] = 1;
			}else $getres[$maskcode] = 0;
			
	 
		
		if($getres){
			$success = 0;
			$failed = 0;
			foreach($getres as $key => $val){
				if($val==1) $success++;
				else $failed++;			
			}
				
		 
			return true;
		}
		
				
		return true;
		
	}
	 
	function gamereport($photogames=false){
	
		$data['userid'] 	= $this->uid;
		if($photogames){
			$data['photomade'] 	= 0;			
			$data['sendmail'] 	= 0;
		}else{
			$data['play'] 	= 0; 
			$data['win'] 	= 0; 
			$data['lose'] 	= 0; 
		}
		
		$appsid = strip_tags($this->apps->_p('appsid'));
		$appsid = $this->checkappsscheme($appsid);
		
		if(!in_array($appsid,$this->gamesarrayid)) return false;
		
		if($photogames){
			$sql  =" 
					SELECT COUNT(1) total,userid FROM my_games 
					WHERE userid = {$this->uid} AND images <> '' AND images IS NOT NULL AND appsid={$appsid}
					GROUP BY userid";
			 
			$qData = $this->apps->fetch($sql);
			 
			if($qData){
				$data['photomade'] = $qData['total'];
			}
			
			$sql  =" 
					SELECT COUNT(1) total,userid FROM my_games 
					WHERE userid = {$this->uid} AND images <> '' AND images IS NOT NULL AND sendmail=1 AND appsid={$appsid}
					GROUP BY userid";
					
			$qData = $this->apps->fetch($sql);
			if($qData){
				$data['sendmail'] = $qData['total'];
			} 
		}
			
		if(!$photogames){
			$sql  =" 
					SELECT COUNT(1) total,userid FROM my_games 
					WHERE userid = {$this->uid} AND appsid={$appsid}
					GROUP BY userid";
					
			$qData = $this->apps->fetch($sql);
			
			if($qData){
				$data['play'] = $qData['total'];
			}
			
			$sql  =" 
					SELECT COUNT(1) total,userid FROM my_games 
					WHERE userid = {$this->uid} AND appsid={$appsid} AND win=1
					GROUP BY userid";
					
			$qData = $this->apps->fetch($sql);
			if($qData){
				$data['win'] = $qData['total'];
				$data['lose'] = $data['play'] - $qData['total'];
			}
		}
	
		return $data;
	}
	
	function twitterUpdates($realimagespath=false){
		global $ENGINE_PATH,$CONFIG;
		require_once $ENGINE_PATH.'Utility/twitteroauth/OAuth.php';
		require_once $ENGINE_PATH.'Utility/twitteroauth/twitter.class.php';
		
		$getSession = $this->apps->session->getSession('twitter_session','twitter');
	
		$consumerKey = "LoqtVMInWWfel8NVg8EPAPZle";
		$consumerSecret = "CLI3gnLVhn6x62sUnEweYAd2Y6Daw0YTc8FlqBNFCX4aVbeORy";
		$accessToken = $getSession->token;
		$accessTokenSecret = $getSession->secret;
		// ENTER HERE YOUR CREDENTIALS (see readme.txt)
		$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
	
			$data['sendtwitterpost'] = false; 
			$data['message'] = "failed"; 
		try {
			if($CONFIG['TWITURL'])$tweet = $twitter->send(" Hi, i'm auditioning for Gogirl! Look 2014. Wish me luck! #gogirlmagazine #lenovomobileID #GLwithlenovosmartphone {$realimagespath} "); 
			else $tweet = $twitter->send(" Hi, i'm auditioning for Gogirl! Look 2014. Wish me luck! #gogirlmagazine #lenovomobileID #GLwithlenovosmartphone  ",$realimagespath); // you can add $imagePath as second argument
			$data['sendtwitterpost'] = true; 
			$data['message'] = "success"; 
		} catch (TwitterException $e) {
			$data['message'] =  $e->getMessage();
			 
		}
		session_destroy();
		return $data;
	
	}
	
	
}

?>

