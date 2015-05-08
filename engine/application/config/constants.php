<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define('MAIL_TYPE_INCOMING' , 'incoming');
define('MAIL_TYPE_OUTGOING' , 'outgoing');

define('MAIL_PRIORITY_NORMAL', 'B');
define('MAIL_PRIORITY_CONFIDENTIAL', 'K');
define('MAIL_PRIORITY_IMPORTANT', 'P');
define('MAIL_PRIORITY_SECURE', 'R');
DEFINE('MAIL_PRIORITY_EXT_SECURE', 'SR');


/* BOOTH SIDE */
define('MAIL_STATUS_NEW', 0);
define('MAIL_STATUS_READ', 1);
define('MAIL_STATUS_POSTED', 2);
define('MAIL_STATUS_REPOSTED', 3);

define('MAIL_STATUS_RESPONDED', 9);

define('MAIL_STATUS_APPROVAL', 10);
define('MAIL_STATUS_APPROVED', 11);
define('MAIL_STATUS_SIGN', 15);
define('MAIL_STATUS_SIGNED', 16);
define('MAIL_STATUS_CLOSED', 20);

define('SIDE_RECEIVER', 'receiver');
define('SIDE_SENDER', 'sender');

define('SURAT_KELUAR_BIASA', 'biasa');
define('SURAT_KELUAR_NODIN', 'notadinas');
define('SURAT_KELUAR_TUGAS', 'surattugas');

/* End of file constants.php */
/* Location: ./application/config/constants.php */