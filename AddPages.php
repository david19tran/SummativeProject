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
<li><a href="index.php">Home</a></li>
</ul>
</nav>
</div>
<div id ="Content">
  <table width="800" border="0" align="center">
    <tr>
      <td><form id="AddPagesForm" name="AddPagesForm" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="0" border="0" align="center">
          <tr>
            <td><label for="NewPage"></label>
              <input type="text" name="NewPage" id="NewPage" /></td>
            <td><input type="submit" name="AddPageButton" id="AddPageButton" value="Add Page" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="AddPagesForm" /> 
        </form>
        <table width="800" border="0">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?php if ($totalRows_Pages > 0) { // Show if recordset not empty ?>
                <?php do { ?>
                  <table width="400" border="0" align="center">
                    <tr>
                      <td width="166"><?php echo $row_Pages['PageName']; ?></td>
                      <td width="224"><a href="EditPage.php?ID=<?php echo $row_Pages['ID']; ?>">Edit Page Content</a></td>
                      </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><form id="DeletePageForm" name="DeletePageForm" method="post" action="">
                        <input name="DeletPagehiddenField" type="hidden" id="DeletPagehiddenField" value="<?php echo $row_Pages['ID']; ?>" />
                        <input type="submit" name="DeletePageButton" id="DeletePageButton" value="Delete Page" />
                      </form></td>
                      </tr>
                  </table>
                  <?php } while ($row_Pages = mysql_fetch_assoc($Pages)); ?>
              <?php } // Show if recordset not empty ?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
<div id ="Footer">Copyright </div>
</div>
</body>
</html>
<?php
mysql_free_result($Pages);
?>
