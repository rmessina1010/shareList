<?

error_reporting  (E_ERROR);
ini_set ("display_errors", true);
ini_set ("mysql.connect_timeout", 1200);
ini_set ("default_socket_timeout", 1200);
define('SCHEME', isset($_SERVER['HTTPS']) ? 'https://' : 'http://');
define('DBNAME','your_DB');
define('HOST',  getenv('HOST') ?:'localhost');
define('UPASS', getenv('UPASS') ?: 'password');
define('UACC', getenv('UACC') ?: 'youraccount');
define('ADMIN_MAIL', getenv('ADMIN_MAIL') ?: 'youremail@email.com');// Your Email address
define('MAIL_HOST', getenv('MAIL_HOST') ?:'yoursite.com');// Your Email Host name
define('ADMIN_MAIL_PASS', getenv('ADMIN_MAIL_PASS') ?: 'your pass');// Your (Admin)Email User Pass
define('ERR_404','_404.php');// It's handy to define ERRs as Constants
define('T_OFFSET',MERID*3600);
define('DB_TYP_DEF','mysql');		//Default DB type.. new as of 5.0
define('APPLY_SC', false);		//Default DB type.. new as of 5.0
