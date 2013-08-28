<?php
//WRITTEN 14/09/06 REVISED	02/12/07
class db {
 var $host = 'handsometoad.db.10398159.hostedresource.com';
 var $port;
 var $username = 'handsometoad';
 var $password = 'Mypsychowife1#';
 var $dbname = 'handsometoad';
 var $dblink; // connection
 var $dbresult; // query result
 var $suppress = false; // query result
 var $debug = false; // debug value

 // config (run when class is initialised)
 function db($debug = false) {
  global $config;
  if ($debug) {$this->debug = true;}
  $this->host = !empty($config['db']['host']) ? $config['db']['host'] : 'handsometoad.db.10398159.hostedresource.com';
  $this->port = !empty($config['db']['port']) ? $config['db']['port'] : '';
  $this->username = !empty($config['db']['username']) ? $config['db']['username'] : 'handsometoad';
  $this->password = !empty($config['db']['password']) ? $config['db']['password'] : 'Mypsychowife1#';
  $this->dbname = !empty($config['db']['name']) ? $config['db']['name'] : 'handsometoad';
  $this->connect();
   
  if($debug)
   echo $this->host." ".$this->port." ".$this->username." ".$this->password."\n";
 }
 
 // connect to db
 function connect() {
  if (!is_resource($this->dblink)) {
   $this->dblink = @mysql_connect($this->host.":".$this->port, $this->username, $this->password);
  }
  
  if ($this->dbname) {
   @mysql_select_db($this->dbname, $this->dblink);
  }
 }
 
 // check connection
 function checkConnect() {
  if(!$this->dblink) return false; else return true;
 }
 
 function close() {
  if(is_resource($this->dblink)) {
   mysql_close($this->dblink);
  }
 }
 
 // query
 function query($sql) {
  if ($this->debug && $_SERVER['REMOTE_ADDR'] == '82.32.227.167') {
   echo $sql.'<br />';
  }
  if (!$this->checkConnect()) {
   if(!$this->suppress) echo "Could not connect to MySQL<br />";
   return false;
  }
  $this->dbresult = mysql_query($sql, $this->dblink);
  if ($this->dbresult) {
   return true;
  } else{
   if(!$this->suppress){
    echo "MySQL error in query: ".$sql."<br />";
    echo "MySQL says: ".mysql_errno($this->dblink)." ".mysql_error($this->dblink)."<br />";
   }
   return false;
  }
 }
 
 // number of rows
 function numRows() {
  return mysql_num_rows($this->dbresult);
 }
 
 // get array
 function getArray() {
  $row = @mysql_fetch_assoc($this->dbresult);
  if ($row) {return $row;} else {return false;}
 }
 
 // get array
 function getFullArray() {
  $i = 0;
  while ($arr[$i++] = mysql_fetch_assoc($this->dbresult));
  array_pop($arr);
  if ($arr) {
   return $arr;
  } else {
   return false;
  }
 }
 
 // escape a string
 function escapeString($string) {
  return mysql_real_escape_string($string);
 }
 function escStr($string){
  return mysql_real_escape_string($string);
 }
 // get insert id
 function getInsID() {
  return mysql_insert_id($this->dblink);
 }
 function getAffRows() {
  return mysql_affected_rows($this->dblink);
 }
}
?>