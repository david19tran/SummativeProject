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

mysql_select_db($database_Basketball, $Basketball);
$query_CMSPage = "SELECT * FROM pages ORDER BY `Timestamp` ASC";
$CMSPage = mysql_query($query_CMSPage, $Basketball) or die(mysql_error());
$row_CMSPage = mysql_fetch_assoc($CMSPage);
$totalRows_CMSPage = mysql_num_rows($CMSPage);

$colname_PageContent = "-1";
if (isset($_GET['ID'])) {
  $colname_PageContent = $_GET['ID'];
}
mysql_select_db($database_Basketball, $Basketball);
$query_PageContent = sprintf("SELECT * FROM pages WHERE ID = %s", GetSQLValueString($colname_PageContent, "int"));
$PageContent = mysql_query($query_PageContent, $Basketball) or die(mysql_error());
$row_PageContent = mysql_fetch_assoc($PageContent);
$totalRows_PageContent = mysql_num_rows($PageContent);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../CSS/Layout.css" rel="stylesheet" type="text/css" />
<link href="../CSS/Menu.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_PageContent['PageName']; ?></title>
<meta name ="keywords" content="h<?php echo $row_PageContent['Keywords']; ?>" />
<meta  name = "description" content = "<?php echo $row_PageContent['PageContent']; ?>" />
</head>

<body> 
<div id ="Container">
<div id ="Header"></div>
<div id ="NavBar">
<nav>
<ul>
<li><a href="index.php">Home</a></li>
<?php if ($totalRows_CMSPage > 0) { // Show if recordset not empty ?>
  <?php do { ?>
    <li><a href="SubPage.php?ID=<?php echo $row_CMSPage['ID']; ?>"><?php echo $row_CMSPage['PageName']; ?></a></li>
    <?php } while ($row_CMSPage = mysql_fetch_assoc($CMSPage)); ?>
  <?php } // Show if recordset not empty ?>
</ul>
</nav>
</div>
<div id ="Content">
  <h1><?php echo $row_PageContent['PageName']; ?></h1>
  <p><?php echo $row_PageContent['PageContent']; ?></p>
</div>
<div id ="Footer">Copyright</div>
</div>
</body>
</html>
<?php
mysql_free_result($CMSPage);

mysql_free_result($PageContent);
?>
