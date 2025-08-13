<?php 
require_once('../application/core/core_init.php');
$installFile = "install.keydera";

if (is_file($installFile)) {
  $api = new L1c3n5380x4P1();

  $errors = false;
  $today = date('Y-m-d');
  $csrf_token_rand = substr(str_shuffle(MD5(microtime())), 0, 10);
  $csrf_cookie_rand = substr(str_shuffle(MD5(microtime())), 0, 10);
  $session_rand = substr(str_shuffle(MD5(microtime())), 0, 10);
  $encrypt_rand = substr(str_shuffle(MD5(microtime())), 0, 20);
  $db_file = '../application/config/database.php';
  $db_file_sample = 'database-sample.php';
  $database_dump_file = 'database.sql';
  $config_file = '../application/config/config.php';
  $config_file_sample = 'config-sample.php';
  $htaccess_file = '../.htaccess';
  $version_files_dir = '../version-files';
  $logs_dir = '../application/logs';
  $core_dir = '../application/core';

  @chmod($installFile,0777);
  @chmod($config_file,0777);
  @chmod($config_file_sample,0777);
  $step = isset($_GET['step']) ? $_GET['step'] : '';
  ?>

<div class="container main_body"> 
<div class="section">
  <div class="column is-three-fifths is-offset-one-fifth">
    <center>
      <img class="p-t-lg m-t-sm" src="../assets/images/logo-dark.svg" width="250" alt="Keydera">
      <h4 class="title is-4 p-t-md p-b-md" style="margin-right: -5px;margin-left: -5px;">
      Welcome to the installation wizard!
    </h4></center>
  </div>
  <div class="column is-6 is-offset-3">
    <?php 
    if($step==''){
      $update_data = $api->get_latest_version();
      if(!empty($update_data['latest_version'])&&(version_compare($update_data['latest_version'],  $api->get_current_version())>0)){ ?>
      <center><article class="message is-warning">
      <div class="message-body">
       New Keydera version <?php echo $update_data['latest_version']; ?> available, <a href="https://codecanyon.net/downloads" target="_blank" rel="noopener noreferrer">download now</a>!
      </div>
    </article></center><br>
    <?php }
    } ?>
    <div class="box"><?php
 switch ($step) {
        default: ?>  
  <div class="tabs is-fullwidth">
    <ul>
      <li class="is-active">
        <a>
          <span><b>Requirements</b></span>
        </a>
      </li>
      <li>
        <a>
          <span>Verify</span>
        </a>
      </li>
      <li>
        <a>
          <span>Database</span>
        </a>
      </li>
      <li>
        <a>
          <span>Finish!</span>
        </a>
      </li>
    </ul>
  </div>                                                      
<?php 
if(version_compare(PHP_VERSION, '5.6.0')<0){
$errors = true;
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Current PHP version is ".phpversion()."! minimum PHP 5.6 or higher required.</div>";
}else{
  echo "<div class='notification is-success' style='padding:12px;'><i class='fa fa-check p-r-xs'></i> You are running PHP version ".phpversion()."</div>";
}
if(!extension_loaded('pdo')){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> PDO PHP extension is missing!</div>";
}else{
  echo "<div class='notification is-success' style='padding:12px;'><i class='fa fa-check p-r-xs'></i> PDO PHP extension is available.</div>";
}
if(!extension_loaded('openssl')){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Openssl PHP extension is missing!</div>";
}else{
  echo "<div class='notification is-success' style='padding:12px;'><i class='fa fa-check p-r-xs'></i> Openssl PHP extension is available.</div>";
}
if(!extension_loaded('curl')){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Curl PHP extension is missing!</div>";
}else{
  echo "<div class='notification is-success' style='padding:12px;'><i class='fa fa-check p-r-xs'></i> Curl PHP extension is available.</div>";
}
if(!extension_loaded('zip')||!class_exists('ZipArchive')){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Zip PHP extension is missing!</div>";
}else{
  echo "<div class='notification is-success' style='padding:12px;'><i class='fa fa-check p-r-xs'></i> Zip PHP extension is available.</div>";
}
if(!is_writeable($db_file)){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Database file (config/database.php) is not writable!</div>";
}
if(!is_writeable($config_file)){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Configuration file (config/config.php) is not writable!</div>";
}
if(!is_writeable($installFile)){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Installation file (".$installFile.") is not writable!</div>";
}
if(!is_writeable($version_files_dir)){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Versions folder (".$version_files_dir.") is not writable!</div>";
}
if(!is_writeable($logs_dir)){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Logs folder (".$logs_dir.") is not writable!</div>";
}
if(!is_writeable($core_dir)){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> Core folder (".$core_dir.") is not writable!</div>";
}
if(!file_exists($htaccess_file)){
  $errors = true; 
  echo "<div class='notification is-danger' style='padding:12px;'><i class='fa fa-times p-r-xs'></i> .htaccess file (".$htaccess_file.") is missing!</div>";
}
?>
<div class="columns is-mobile is-centered is-vcentered">
  <div class="column">
    <p class='help has-text-grey p-t-xs has-text-centered'>By clicking "Next", you agree to our <a class="has-text-grey" href="https://www.craadly.com">Terms & Privacy Policy</a>.</p>
  </div>
  <div class="column is-narrow">
    <div style='text-align: right;'>
      <?php if($errors==true){ ?>
        <a href="#" class="button is-link" disabled>Next</a>
      <?php }else{ ?>
        <a href="index.php?step=0" class="button is-link">Next</a>
      <?php } ?>
    </div>
  </div>
</div>                 
<?php
break;
case "0": 
?>
<div class="tabs is-fullwidth">
  <ul>
    <li>
      <a>
        <span><i class="fas fa-check-circle"></i> Requirements</span>
      </a>
    </li>
    <li class="is-active">
      <a>
        <span><b>Verify</b></span>
      </a>
    </li>
    <li>
      <a>
        <span>Database</span>
      </a>
    </li>
    <li>
      <a>
        <span>Finish!</span>
      </a>
    </li>
  </ul>
</div>   
<?php
    $license_code = null;
    $client_name = null;
if(!empty($_POST['license'])&&!empty($_POST['client'])&&!empty($_POST['email'])){
    $license_code = strip_tags(trim((string) $_POST["license"]));
    $client_name = strip_tags(trim((string) $_POST["client"]));

    $verify_response = $api->activate_license($license_code, $client_name, strip_tags(trim((string) $_POST['email'])));

    if(empty($verify_response)){
     $msg = 'Server is unavailable at the moment, please try again.';
    }else{
      $msg = $verify_response['message'];
    }

    if ($verify_response['status'] != true) { ?>
   <form action="index.php?step=0" method="POST">
   <div class="notification is-danger"><?php echo ucfirst($msg); ?></div>
  <div class="field">
    <label class="label">Purchase code</a></small></label>
    <div class="control">
      <input class="input" type="text" placeholder="Enter your purchase code" name="license" required>
    </div>
  </div>
  <div class="field">
    <label class="label">Envato username</label>
    <div class="control">
      <input class="input" type="text" placeholder="Enter your envato username" name="client" required>
    </div>
  </div>
  <div class="field">
    <label class="label">Email address <small class="has-text-weight-normal has-text-grey"> (It will be used for sending password reset emails.)</small></label>
    <div class="control">
      <input class="input" type="email" placeholder="Enter your email address" name="email" required>
    </div>
  </div>
  <div style='text-align: right;'>
  <button type="submit" class="button is-link">Verify</button>
</div>
</form><?php 
 } else { 
?>
  <form action="index.php?step=1" method="POST">
    <div class="notification is-success"><?php echo ucfirst($msg); ?></div>
    <input type="hidden" name="user_email" id="user_email" value="<?php echo strip_tags(trim($_POST['email'])); ?>">
    <input type="hidden" name="prc3" id="prc3" value="<?php echo ucfirst($verify_response['status']); ?>">
    <input type="hidden" name="sql_data" id="sql_data" value="<?php echo $verify_response['data']; ?>">
    <div class="columns is-mobile is-centered is-vcentered">
      <div class="column">
        <p class='p-t-xs has-text-centered'>Click next to proceed with the installation.</p>
      </div>
      <div class="column is-narrow">
        <div style='text-align: right;'>
          <button type="submit" class="button is-link">Next</button>
        </div>
      </div>
    </div>
  </form> 
<?php
  } 
}else{  ?>
  <form action="index.php?step=0" method="POST">
    <div class="field">
      <label class="label">Purchase code</label>
      <div class="control">
        <input class="input" type="text" placeholder="Enter random purchase code" name="license" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Envato username</label>
      <div class="control">
        <input class="input" type="text" placeholder="Enter random envato username" name="client" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Email address</label>
      <div class="control">
        <input class="input" type="email" placeholder="Enter random email address" name="email" required>
      </div>
    </div>
    <div style='text-align: right;'>
      <button type="submit" class="button is-link">Verify</button>
    </div>
  </form>
<?php } ?>
<?php
  break;
  case "1":
  ?>
<div class="tabs is-fullwidth">
  <ul>
    <li>
      <a>
        <span><i class="fas fa-check-circle"></i> Requirements</span>
      </a>
    </li>
    <li>
      <a>
        <span><i class="fas fa-check-circle"></i> Verify</span>
      </a>
    </li>
    <li class="is-active">
      <a>
        <span><b>Database</b></span>
      </a>
    </li>
    <li>
      <a>
        <span>Finish!</span>
      </a>
    </li>
  </ul>
</div> 
<?php
$user_email = null;
$db_host = null;
$db_port = null;
$db_user = null;
$db_pass = null;
$db_name = null;
if($_POST&&(isset($_POST["prc3"]))&&!empty($_POST["sql_data"])&&!empty($_POST["user_email"])){
  $valid = $_POST["prc3"];
  $sql_id = $_POST["sql_data"];
  $user_email = strip_tags(trim((string) $_POST["user_email"]));
  $db_host = (isset($_POST["host"]))?strip_tags(trim($_POST["host"])):null;
  $db_port = (isset($_POST["port"]))?strip_tags(trim($_POST["port"])):3306;
  $db_user = (isset($_POST["user"]))?strip_tags(trim($_POST["user"])):null;
  $db_pass = (isset($_POST["pass"]))?strip_tags(trim($_POST["pass"])):null;
  $db_name = (isset($_POST["name"]))?strip_tags(trim($_POST["name"])):null;
if(!empty($db_host)){
try {

$pdof = new PDO("mysql:host=$db_host;port=$db_port;", $db_user, $db_pass);
$pdof->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mysql_ver = $pdof->query('select version()')->fetchColumn();
if(version_compare($mysql_ver, '5.6.0') < 0){ ?>
  <div class='notification is-danger'>You are running MySQL <?php echo $mysql_ver; ?>, minimum requirement is MySQL 5.6 or higher. Please upgrade and re-run the installation or contact support.</div>
<?php
die();
}

$api->download_sql($sql_id, $api->get_current_version());
$dbname = "`".str_replace("`","``",$db_name)."`";
$pdof->query("CREATE DATABASE IF NOT EXISTS $dbname");
$pdof->query("use $dbname");

$templine = '';
$lines = file($database_dump_file);
foreach ($lines as $line) {
  if (substr($line, 0, 2) == '--' || $line == '')
    continue;
    $templine .= $line;
    $query = false;
  if (substr(trim($line), -1, 1) == ';') {
    $query = $pdof->query($templine);
    $templine = '';
  }
}
$sql0 = "UPDATE auth_users SET au_email = :user_email WHERE au_id = 1;";
$stmt= $pdof->prepare($sql0);
$stmt->execute(array('user_email' => $user_email));
$pdof->query("COMMIT;");

@chmod($database_dump_file,0777);
if(is_writeable($database_dump_file))
{
    unlink($database_dump_file);
}

if (!is_file($htaccess_file)) {
  if(is_writeable($htaccess_file))
  {
    $htaccess_raw = <<<'RAW'
# BEGIN Keydera

DirectoryIndex index.php index.html

RewriteEngine on

# Uncomment the lines below to Force-HTTPS, if you wish you can also Force-HTTPS from config.php.
# RewriteCond %{HTTPS} !on
# RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI}

# Must have security headers when using HTTPS
# <IfModule mod_headers.c>
#   Header always set Strict-Transport-Security "max-age=31536000;includeSubDomains"
#   Header always edit Set-Cookie ^(.*)$ $1;SameSite=None;Secure
# </IfModule>

# Uncomment to add trailing slash in URL.
# RewriteCond %{REQUEST_URI} /+[^\.]+$
# RewriteRule ^(.+[^/])$ %{REQUEST_URI}/ [R=301,L]

# Uncomment to remove extra trailing slash from URL.
# RewriteCond %{REQUEST_FILENAME} !-d
# RewriteCond %{REQUEST_URI} (.+)/$
# RewriteRule ^ %1 [R=301,L]

# Useful security headers
<IfModule mod_headers.c>
  Header set X-XSS-Protection "1; mode=block"
  Header always append X-Frame-Options SAMEORIGIN
  Header set X-Content-Type-Options nosniff
</IfModule>

<FilesMatch ".(svg|jpg|jpeg|png|ico|js|css)$">
  Header set Cache-Control "max-age=84600, public"
</FilesMatch>

# On some servers the default rewrite rule may not work, in that case, you can try this.
# RewriteCond $1 !^(index\.php|assets|images|js|css|vendor|favicon.png)
# RewriteCond %(REQUEST_FILENAME) !-f
# RewriteCond %(REQUEST_FILENAME) !-d
# RewriteRule ^(.*)$ ./index.php/$1 [L]

# Default URL rewrite rules.
RewriteCond $1 !^(index\.php|assets|images|js|css|vendor|favicon.png)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ ./index.php?/$1 [L,QSA]

# END Keydera

RAW;
    @file_put_contents($htaccess_file,$htaccess_raw);
  }
}

if($db_port!=3306){
  $trans = array("{[DB_NAME]}" => $db_name, "{[DB_USER]}" => $db_user, "{[DB_PASS]}" => $db_pass, "{[DB_HOST]}" => $db_host, "{[DB_PORT]}" => ";port=".$db_port, "{[DB_DEFAULT_PORT]}" => '$db["default"]["port"] = '.$db_port.';', "{[DB_HOST_PORT]}" => $db_host.":".$db_port);
}else{
  $trans = array("{[DB_NAME]}" => $db_name, "{[DB_USER]}" => $db_user, "{[DB_PASS]}" => $db_pass, "{[DB_HOST]}" => $db_host, "{[DB_PORT]}" => null, "{[DB_DEFAULT_PORT]}" => null, "{[DB_HOST_PORT]}" => $db_host);
}

if(is_writeable($db_file)){
  file_put_contents($db_file,strtr(file_get_contents($db_file_sample), $trans));
}
else{ ?>
<div class='notification is-danger'>Database file (<strong><?php echo $db_file; ?></strong>) is not writable, you should change the file permission first then retry this step or you can change the db settings yourself.</div>
<?php }
$http_or_https = (((isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on")) or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) ? 'https://' : 'http://');
$redir = $http_or_https;
$redir .= $_SERVER['HTTP_HOST'];
$redir .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$redir = str_replace('/install/','',$redir); 
$trans1 = array("{[BASE_URL]}" => $redir, "{[CSRFT]}" => $csrf_token_rand, "{[CSRFC]}" => $csrf_cookie_rand, "{[ENCRK]}" => $encrypt_rand, "{[SESSC]}" => $session_rand);

if(is_writeable($config_file))
{
  file_put_contents($config_file,strtr(file_get_contents($config_file_sample), $trans1));
}
else{
?>
<div class='notification is-danger'>Configuration file (<strong><?php echo $config_file; ?></strong>) is not writable, you should change the file permission first then retry this step or you can change the config settings yourself.</div>
<?php
} ?>
<form action="index.php?step=2" method="POST">
  <div class='notification is-success'>Database was successfully created.</div>
  <input type="hidden" name="prc4" id="prc4" value="true">
  <div class="columns is-mobile is-centered is-vcentered">
    <div class="column">
      <p class='p-t-xs has-text-centered'>Click next to complete the installation.</p>
    </div>
    <div class="column is-narrow">
      <div style='text-align: right;'>
        <button type="submit" class="button is-link">Next</button>
      </div>
    </div>
  </div> 
</form> 
<?php
}
catch (PDOException $err) {
?>
<div class='notification is-danger'>An error occurred, check if the database credentials entered are correct and the selected table is empty.</div>
  <form action="index.php?step=1" method="POST">
    <input type="hidden" name="user_email" id="user_email" value="<?php echo $user_email; ?>">
    <input type="hidden" name="prc3" id="prc3" value="<?php echo $valid; ?>">
    <input type="hidden" name="sql_data" id="sql_data" value="<?php echo $sql_id; ?>">
    <div class="field">
      <label class="label">Database Host</label>
      <div class="control">
        <input class="input" type="text" id="host" placeholder="Your database host" name="host" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Database Port</label>
      <div class="control">
        <input class="input" value="3306" type="number" id="port" placeholder="Your database port" name="port" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Database Username</label>
      <div class="control">
        <input class="input" type="text" id="user" placeholder="Your database username" name="user" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Database Password</label>
      <div class="control">
        <input class="input" type="text" id="pass" placeholder="Your database password" name="pass">
      </div>
    </div>
    <div class="field">
      <label class="label">Database Name</label>
      <div class="control">
        <input class="input" type="text" id="name" placeholder="Your database name" name="name" required>
      </div>
    </div>
  <div class="columns is-mobile is-centered is-vcentered">
    <div class="column">
      <p class='help has-text-grey has-text-centered p-t-xs'>Database creation may take some time, please don't refresh.</p>
    </div>
    <div class="column is-narrow">
      <div style='text-align: right;'>
        <button type="submit" class="button is-link">Create</button>           
      </div>
    </div>
  </div>  
</form>
<?php }
}
else
{ ?>
  <form action="index.php?step=1" method="POST">
    <input type="hidden" name="user_email" id="user_email" value="<?php echo $user_email; ?>">
    <input type="hidden" name="prc3" id="prc3" value="<?php echo $valid; ?>">
    <input type="hidden" name="sql_data" id="sql_data" value="<?php echo $sql_id; ?>">
    <div class="field">
      <label class="label">Database Host</label>
      <div class="control">
        <input class="input" type="text" id="host" placeholder="Your database host" name="host" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Database Port</label>
      <div class="control">
        <input class="input" value="3306" type="number" id="port" placeholder="Your database port" name="port" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Database Username</label>
      <div class="control">
        <input class="input" type="text" id="user" placeholder="Your database username" name="user" required>
      </div>
    </div>
    <div class="field">
      <label class="label">Database Password</label>
      <div class="control">
        <input class="input" type="text" id="pass" placeholder="Your database password" name="pass">
      </div>
    </div>
    <div class="field">
      <label class="label">Database Name</label>
      <div class="control">
        <input class="input" type="text" id="name" placeholder="Your database name" name="name" required>
      </div>
    </div>
  <div class="columns is-mobile is-centered is-vcentered">
    <div class="column">
      <p class='help has-text-grey has-text-centered p-t-xs'>Database creation may take some time, please don't refresh.</p>
    </div>
    <div class="column is-narrow">
      <div style='text-align: right;'>
        <button type="submit" class="button is-link">Create</button>           
      </div>
    </div>
  </div>
</form>
<?php } ?>
<?php } else { ?>
  <div class='notification is-danger'>Sorry, something went wrong. Please re-run the installation</div>
<?php } ?>
 <?php
  break;
  case "2":
  ?>    
  <div class="tabs is-fullwidth">
  <ul>
    <li>
      <a>
        <span><i class="fas fa-check-circle"></i> Requirements</span>
      </a>
    </li>
    <li>
      <a>
        <span><i class="fas fa-check-circle"></i> Verify</span>
      </a>
    </li>
    <li>
      <a>
        <span><i class="fas fa-check-circle"></i> Database</span>
      </a>
    </li>
    <li class="is-active">
      <a>    
        <span><b>Finish!</b></span>
      </a>
    </li>
  </ul>
</div>
<?php 
if($_POST&&(isset($_POST["prc4"]))){
  $valid = $_POST["prc4"];
  if(is_writeable($installFile))
  {
    unlink($installFile);
  }
  else{ ?>
    <div class='notification is-danger'>Installation file was not removed, you should delete the (<strong><?php echo $installFile; ?></strong>) file to lock the installer.</div>
  <?php }?>
<center><p><strong>Keydera is successfully installed.</strong></p><br>
<p>You can now login using your email or username: <strong>Craadly</strong> and default password: <strong>Keydera.com</strong></p><br><strong>
<p><a class='button is-link' href='../'>Login</a></p></strong>
<br>
<p class='help has-text-grey'>The first thing you should do is change your account details.</p>
</center>
<?php }
else { ?>
<div class='notification is-danger'>Sorry, something went wrong. Please re-run the installation.</div>
<?php } ?>
<?php
  break;}
  ?>    
    </div>
    <center>
    <p class="has-text-grey p-b-sm"><small>You are installing Keydera <?php echo $api->get_current_version();?></small></p>
    <a class="has-text-grey-darker has-text-weight-semibold" href="mailto:support@craadly.com help with Keydera <?php echo $api->get_current_version();?> Installation&body=(Note: Please explain the issue you are having along with the screenshot below and don't forget to include your purchase code.)">Need Help <i class="fas fa-question-circle"></i></a>
    </center>
    </div>
  </div> 
</div>
<?php } else{ ?>
<div class="container main_body"> 
  <div class="section" >
    <center>
      <h1 class="title p-t-xxl m-t-xl">
        Keydera installer is locked, check documentation.
      </h1>
    </center>
  </div>
</div>
<?php } ?>

