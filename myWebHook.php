<?php

// Your remote name
$remote = "origin";

// Aliases for branches and directories
$aliases = array(
  "master"  => array( "path/to/production" )
  //,"staging" => "path/to/staging"
  //,"clients" => array( "client1","client2","client3","client4" )
);

// Do you want a log file with web hook posts?
$log = FALSE;




# ===========================================================================
# ==== No Humans Below. Only Honey Badgers. http://youtu.be/4r7wHMg5Yjg =====
# ===========================================================================

$ip = $_SERVER['REMOTE_ADDR'];
$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

if (empty($ip)) {
  header($protocol.' 400 Bad Request');
  die('invalid ip address');
} elseif (empty($_POST['payload'])) {
  header($protocol.' 400 Bad Request');
  die('missing payload');
}

# Receive POST data
$payload = json_decode($_POST['payload'], true);

# Make sure we have something
if(empty($payload)) {
  die("<img src='http://i.qkme.me/3sst5f.jpg' />");
}

# Log posts
if($log) {
  file_put_contents($_SERVER['SCRIPT_FILENAME'].'.log',"Web Hook Post: ".date("F j, Y, g:i a")."\n".$_POST['payload']."\n\n", FILE_APPEND);
}

# Attempt to detect branch name
if( isset($payload['canon_url']) && strpos($payload['canon_url'],"bitbucket")!== FALSE ) {
    $lastCommit = $payload['commits'][ count($payload['commits'])-1 ];
    $branch     = isset($lastCommit['branches']) && !empty($lastCommit['branches']) ? $lastCommit['branches'][0]:$lastCommit['branch'];
} else if( isset($payload['repository']['url']) && strpos($payload['repository']['url'],"github")!==FALSE ) {
  $branch = str_replace("refs/heads/","",$payload['ref']);
} else {
  $branch = "NO-BRANCH-DETECTED-DO-NOT-CREATE-A-FOLDER-WITH-THIS-NAME";
}

# Container for directories
$dirs_to_update = array();

# Add branch name if that directory exists
if( is_dir($branch) ) {
  $dirs_to_update[] = $branch;
}

# Check for branch aliases
if( array_key_exists($branch,$aliases) ) {

  # If we have an array of aliases, merge it in
  if( is_array($aliases[$branch]) ) {
    $list = $aliases[$branch];
    # delete non directories
    $list = array_filter($list,"filterNonDir");
    # merge with existing directories
    $dirs_to_update = array_merge($dirs_to_update,$list);
  }
  else if(is_dir($aliases[$branch])) {
    $dirs_to_update[] = $aliases[$branch];
  }
}

# Check to see if there is nothing to do
if( empty($dirs_to_update) ) {
  die("Apparently there is nothing to update for this branch\n");
}

# Capture current directory
$original_dir = getcwd();

# Loop through the directories
foreach($dirs_to_update as $dir) {
  chdir($dir);
  exec("git pull $remote $branch");
  chdir($original_dir);
}

# The End

# =====
# util
# ====
function filterNonDir($path) {
  # If path doesn't exist, take it out of the array
  return is_dir($path) ? $path:FALSE;
}
