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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "EditPageForm")) {
  $updateSQL = sprintf("UPDATE pages SET PageContent=%s, Keywords=%s, MetaDesc=%s WHERE ID=%s",
                       GetSQLValueString($_POST['PageContent'], "text"),
                       GetSQLValueString($_POST['Keywords'], "text"),
                       GetSQLValueString($_POST['MetaDesc'], "text"),
                       GetSQLValueString($_POST['IDhiddenField'], "int"));

  mysql_select_db($database_Basketball, $Basketball);
  $Result1 = mysql_query($updateSQL, $Basketball) or die(mysql_error());

  $updateGoTo = "AddPages.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_EditPage = "-1";
if (isset($_GET['ID'])) {
  $colname_EditPage = $_GET['ID'];
}
mysql_select_db($database_Basketball, $Basketball);
$query_EditPage = sprintf("SELECT * FROM pages WHERE ID = %s", GetSQLValueString($colname_EditPage, "int"));
$EditPage = mysql_query($query_EditPage, $Basketball) or die(mysql_error());
$row_EditPage = mysql_fetch_assoc($EditPage);
$totalRows_EditPage = mysql_num_rows($EditPage);
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
</ul>
</nav>
</div>
<div id ="Content">
  <h1>Edit Page</h1>
  <table width="800" border="0" align="center">
    <tr>
      <td><form id="EditPageForm" name="EditPageForm" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="400" border="0" align="center">
          <tr>
            <td>Content</td>
          </tr>
          <tr>
            <td><label for="PageContent"></label>
              <textarea name="PageContent" id="PageContent" cols="45" rows="5"><?php echo $row_EditPage['PageContent']; ?></textarea></td>
          </tr>
          <tr>
            <td>Keywords</td>
          </tr>
          <tr>
            <td><label for="Keywords"></label>
              <textarea name="Keywords" id="Keywords" cols="45" rows="5"><?php echo $row_EditPage['Keywords']; ?></textarea></td>
          </tr>
          <tr>
            <td>Meta Description</td>
          </tr>
          <tr>
            <td><label for="MetaDesc"></label>
              <textarea name="MetaDesc" id="MetaDesc" cols="45" rows="5"><?php echo $row_EditPage['MetaDesc']; ?></textarea></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input type="submit" name="Update" id="Update" value="Update Page" />
              <input name="IDhiddenField" type="hidden" id="IDhiddenField" value="<?php echo $row_EditPage['ID']; ?>" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          </table>
        <input type="hidden" name="MM_update" value="EditPageForm" />
      </form></td>
    </tr>
  </table>
  <p>&nbsp;</p>
</div>
<div id ="Footer">Copyright </div>
</div>
</body>
</html>
<?php
mysql_free_result($EditPage);
?>
