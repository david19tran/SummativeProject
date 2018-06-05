<?php require_once('../Connections/Basketball.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "AddPagesForm")) {
  $insertSQL = sprintf("INSERT INTO pages (PageName) VALUES (%s)",
                       GetSQLValueString($_POST['NewPage'], "text"));

  mysql_select_db($database_Basketball, $Basketball);
  $Result1 = mysql_query($insertSQL, $Basketball) or die(mysql_error());

  $insertGoTo = "AddPages.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST['DeletPagehiddenField'])) && ($_POST['DeletPagehiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM pages WHERE ID=%s",
                       GetSQLValueString($_POST['DeletPagehiddenField'], "int"));

  mysql_select_db($database_Basketball, $Basketball);
  $Result1 = mysql_query($deleteSQL, $Basketball) or die(mysql_error());

  $deleteGoTo = "AddPages.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

mysql_select_db($database_Basketball, $Basketball);
$query_Pages = "SELECT * FROM pages";
$Pages = mysql_query($query_Pages, $Basketball) or die(mysql_error());
$row_Pages = mysql_fetch_assoc($Pages);
$totalRows_Pages = mysql_num_rows($Pages);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../CSS/Layout.css" rel="stylesheet" type="text/css" />
<link href="../CSS/Menu.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body> 
<div id ="Container">
<div id ="Header"></div>
<div id ="NavBar">
<nav>
<ul>
<li><a href="#">Home</a></li>
<li><a href="#">Services</a></li>
<li><a href="#">About</a></li>
<li><a href="#">Contact</a></li>
</ul>
</nav>
</div>
