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
  <h1>Header here </h1>
  <p>Lorem ipsum dolor sit amet, massa vestibulum, vel quam bibendum amet vulputate orci massa, varius cursus justo commodo non volutpat, at consequatur, vitae amet. Vehicula parturient enim fermentum tristique tortor, ligula pellentesque, dis mollis, nec eu molestie arcu morbi. Blandit blandit, integer in erat libero, nec convallis urna, integer suscipit nulla est. Posuere ut luctus urna, eu lorem integer, lorem donec mauris nulla pede montes. Aliquam viverra vitae sapien curabitur, luctus sit sit mi. Per elit id nec, nunc pellentesque nam tellus, enim ultrices pede metus, aenean et adipiscing, aliquam ac massa suspendisse vitae pede pellentesque. Risus eleifend, nec velit mauris duis ante sed, massa nam justo ipsum nunc, nisl leo, urna rhoncus. </p>
  <p>&nbsp;</p>
  <p>Neque velit. Amet arcu felis mattis, vitae cras libero dui lacus scelerisque ac, felis ultricies, accumsan dolor justo auctor id. Consequatur vulputate ut, pharetra interdum rutrum erat mi ante dolor. Consequat fusce elit perspiciatis, tincidunt sollicitudin quam rerum enim imperdiet quam, ad ac quis a arcu eu, eget donec in nam, condimentum urna bibendum.</p>
</div>
<div id ="Footer">Copyright | <a href="Login.php">Admin</a></div>
</div>
</body>
</html>
<?php
mysql_free_result($CMSPage);
?>
