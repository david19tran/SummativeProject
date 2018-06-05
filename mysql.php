<?php
// If this file is not included from the MMHTTPDB possible hacking problem.
if (!function_exists('create_error')){
	die();
}

define('MYSQL_NOT_EXISTS', create_error("Your PHP server doesn't have the MySQL module loaded or you can't use the mysql_(p)connect functions."));
define('CONN_NOT_OPEN_GET_TABLES', create_error('The Connection could not be opened when trying to retrieve the tables.'));
define('CONN_NOT_OPEN_GET_DB_LIST', create_error('The Connection could not be opened when trying to retrieve the database list.'));
			 
if (!function_exists('mysql_connect') || !function_exists('mysql_pconnect') || !extension_loaded('mysql')){
	echo MYSQL_NOT_EXISTS;
	die();
}

// Now let's handle the crashes or any other PHP errors that we can catch
function KT_ErrorHandler($errno, $errstr, $errfile, $errline) { 
	global $f, $already_sent;
	$errortype = array ( 
		1   =>  "Error", 
		2   =>  "Warning", 
		4   =>  "Parsing Error", 
		8   =>  "Notice", 
		16  =>  "Core Error", 
		32  =>  "Core Warning", 
		64  =>  "Compile Error", 
		128 =>  "Compile Warning", 
		256 =>  "User Error", 
		512 =>  "User Warning", 
		1024=>  "User Notice",
		2048=>  "E_ALL",
		2049=>  "PHP5 E_STRICT"
	
	);
	$str = sprintf("[%s]\n%s:\t%s\nFile:\t\t'%s'\nLine:\t\t%s\n\n", date('d-m-Y H:i:s'),(isset($errortype[@$errno])?$errortype[@$errno]:('Unknown '.$errno)),@$errstr,@$errfile,@$errline);
	if (error_reporting() != 0) {
			@fwrite($f, $str);
			if (@$errno == 2 && isset($already_sent) && !$already_sent==true){
				$error = '<ERRORS>'."\n";
				$error .= '<ERROR><DESCRIPTION>An Warning Type error appeared. The error is logged into the log file.</DESCRIPTION></ERROR>'."\n";
				$error .= '</ERRORS>'."\n";
				$already_sent = true;
				echo $error;
			}
	}
}
if ($debug_to_file){
		$old_error_handler = set_error_handler("KT_ErrorHandler");
}

class MySqlConnection
{
/*
 // The 'var' keyword is deprecated in PHP5 ... we will define these variables at runtime.
  var $isOpen;
	var $hostname;
	var $database;
	var $username;
	var $password;
	var $timeout;
	var $connectionId;
	var $error;
*/
	function MySqlConnection($ConnectionString, $Timeout, $Host, $DB, $UID, $Pwd)
	{
		$this->isOpen = false;
		$this->timeout = $Timeout;
		$this->error = '';

		if( $Host ) { 
			$this->hostname = $Host;
		}
		elseif( preg_match("/host=([^;]+);/", $ConnectionString, $ret) )  {
			$this->hostname = $ret[1];
		}
		
		if( $DB ) {
			$this->database = $DB;
		}
		elseif( preg_match("/db=([^;]+);/",   $ConnectionString, $ret) ) {
			$this->database = $ret[1];
		}
		
		if( $UID ) {
			$this->username = $UID;
		}
		elseif( preg_match("/uid=([^;]+);/",  $ConnectionString, $ret) ) {
			$this->username = $ret[1];
		}
		
		if( $Pwd ) {
			$this->password = $Pwd;
		}
		elseif( preg_match("/pwd=([^;]+);/",  $ConnectionString, $ret) ) {
			$this->password = $ret[1];
		}
	}

	function Open()
	{
	  $this->connectionId = mysql_connect($this->hostname, $this->username, $this->password);
		if (isset($this->connectionId) && $this->connectionId && is_resource($this->connectionId))
		{
			$this->isOpen = ($this->database == "") ? true : mysql_select_db($this->database, $this->connectionId);
		}
		else
		{
			$this->isOpen = false;
		}	
	}

	function TestOpen()
	{
		return ($this->isOpen) ? '<TEST status=true></TEST>' : $this->HandleException();
	}

	function Close()
	{
		if (is_resource($this->connectionId) && $this->isOpen)
		{
			if (mysql_close($this->connectionId))
			{
				$this->isOpen = false;
				unset($this->connectionId);
			}
		}
	}

	function GetTables($table_name = '')
	{
		$xmlOutput = "";
		if ($this->isOpen && isset($this->connectionId) && is_resource($this->connectionId)){
			// 1. mysql_list_tables and mysql_tablename are deprecated in PHP5
			// 2. For backward compatibility GetTables don't have any parameters
			if ($table_name === ''){
					$table_name = @$_POST['Database'];
			}
			//added backtick for handling reserved words and special characters
			//http://dev.mysql.com/doc/refman/5.0/en/legal-names.html
			$sql = ' SHOW TABLES FROM ' . $this->ensureTicks($table_name) ;
			$results = mysql_query($sql, $this->connectionId) or $this->HandleException();

			$xmlOutput = "<RESULTSET><FIELDS>";

			// Columns are referenced by index, so Schema and
			// Catalog must be specified even though they are not supported

			$xmlOutput .= '<FIELD><NAME>TABLE_CATALOG</NAME></FIELD>';		// column 0 (zero-based)
			$xmlOutput .= '<FIELD><NAME>TABLE_SCHEMA</NAME></FIELD>';		// column 1
			$xmlOutput .= '<FIELD><NAME>TABLE_NAME</NAME></FIELD>';		// column 2

			$xmlOutput .= "</FIELDS><ROWS>";

			if (is_resource($results) && mysql_num_rows($results) > 0){
					while ($row = mysql_fetch_array($results)){
							$xmlOutput .= '<ROW><VALUE/><VALUE/><VALUE>' . $row[0]. '</VALUE></ROW>';	
					}
			}
			$xmlOutput .= "</ROWS></RESULTSET>";

    }
		return $xmlOutput;
	}

	function GetViews()
	{
		// not supported
		return "<RESULTSET><FIELDS></FIELDS><ROWS></ROWS></RESULTSET>";
	}

	function GetProcedures()
	{
		// not supported
		return "<RESULTSET><FIELDS></FIELDS><ROWS></ROWS></RESULTSET>";
	}

	function GetColumnsOfTable($TableName)
	{
		$xmlOutput = "";
		//added backtick for handling reserved words and special characters
		//http://dev.mysql.com/doc/refman/5.0/en/legal-names.html
		$query  = "DESCRIBE ".$this->ensureTicks($TableName);
		$result = mysql_query($query) or $this->HandleException();

		if ($result)
		{
			$xmlOutput = "<RESULTSET><FIELDS>";

			// Columns are referenced by index, so Schema and
			// Catalog must be specified even though they are not supported
			$xmlOutput .= "<FIELD><NAME>TABLE_CATALOG</NAME></FIELD>";		// column 0 (zero-based)
			$xmlOutput .= "<FIELD><NAME>TABLE_SCHEMA</NAME></FIELD>";		// column 1
			$xmlOutput .= "<FIELD><NAME>TABLE_NAME</NAME></FIELD>";			// column 2
			$xmlOutput .= "<FIELD><NAME>COLUMN_NAME</NAME></FIELD>";
			$xmlOutput .= "<FIELD><NAME>DATA_TYPE</NAME></FIELD>";
			$xmlOutput .= "<FIELD><NAME>IS_NULLABLE</NAME></FIELD>";
			$xmlOutput .= "<FIELD><NAME>COLUMN_SIZE</NAME></FIELD>";

			$xmlOutput .= "</FIELDS><ROWS>";

			// The fields returned from DESCRIBE are: Field, Type, Null, Key, Default, Extra
