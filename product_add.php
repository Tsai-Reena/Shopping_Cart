<?php
require_once("connMysql.php");
session_start();
//繫結登入會員資料
$query_RecMember = "SELECT * FROM `seler` WHERE `Username` = '{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember=$RecMember->fetch_assoc();

//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: sellerlogin.php");
}

function GetSQLValueString($theValue, $theType) {
  switch ($theType) {
    case "string":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_ADD_SLASHES) : "";
      break;
    case "int":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
      break;
    case "email":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
      break;
    case "url":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
      break;      
  }
  return $theValue;
}

if(isset($_POST["action"])&&($_POST["action"]=="join")){
	// require_once("connMysql.php");
	$query_insert = "INSERT INTO `product` ( `BrandName`, `ProductName`, `Price`, `Quantity`, `SecondHand`, `Description`, `sellerID`) VALUES (?, ?, ?, ?, ?, ?, ?)";
	$stmt = $db_link->prepare($query_insert);
	$stmt->bind_param("ssssisi", 
		//GetSQLValueString($_POST["m_name"], 'string'),
		GetSQLValueString($_POST["BrandName"], 'string'),
		//password_hash($_POST["m_passwd"], PASSWORD_DEFAULT),
		GetSQLValueString($_POST["ProductName"], 'string'),
		GetSQLValueString($_POST["Price"], 'string'),
		GetSQLValueString($_POST["Quantity"], 'string'),
		//GetSQLValueString($_POST["m_url"], 'url'),
		GetSQLValueString($_POST["SecondHand"], 'int'),
		GetSQLValueString($_POST["Description"], 'string'),
    GetSQLValueString($row_RecMember["ID"], 'string'));
	$stmt->execute();
	$stmt->close();
	//$db_link->close();

  if($_POST["ptype"]==0){
    // $qqq  = "INSERT INTO `pc` ( `productID`) VALUES (SELECT pID FROM `product` WHERE `BrandName`= ?)";
    // $insertion = $db_link->prepare($qqq);
    // $insertion->bind_param("s", GetSQLValueString($_POST["BrandName"], 'string'));
    // $insertion->execute();
    // $insertion->close();
    // $db_link->close();
    $aaa  = "SELECT `pID` FROM `product` WHERE `ProductName`= ?";
    $bbb = $db_link->prepare($aaa);
    $bbb->bind_param("s", $_POST["BrandName"]);
    $bbb->bind_result($ID);	
	  $bbb->fetch();
    $bbb->execute();
    $bbb->close();
    // echo $ID;
    $qqq  = "INSERT INTO `pc` (`productID`) VALUES (?)";
    $insertion = $db_link->prepare($qqq);
    $insertion->bind_param("i", $ID);
    $insertion->execute();
    $insertion->close();
    $db_link->close();
  }   
  else if($_POST["ptype"]==1){
    // $qqq  = "INSERT INTO `tablet` ( `productID`) VALUES (SELECT pID FROM `product` WHERE `BrandName`= ?)";
    // $insertion = $db_link->prepare($qqq);
    // $insertion->bind_param("s", GetSQLValueString($_POST["BrandName"], 'string'));
    // $insertion->execute();
    // $insertion->close();
    // $db_link->close();
    $aaa  = "SELECT `pID` FROM `product` WHERE `ProductName`= ?";
    $bbb = $db_link->prepare($aaa);
    $bbb->bind_param("s", $_POST["BrandName"]);
    $bbb->bind_result($ID);	
	  $bbb->fetch();
    $bbb->execute();
    $bbb->close();
    // echo $ID;
    $qqq  = "INSERT INTO `tablet` (`productID`) VALUES (?)";
    $insertion = $db_link->prepare($qqq);
    $insertion->bind_param("i", $ID);
    $insertion->execute();
    $insertion->close();
    $db_link->close();
  }
  else if($_POST["ptype"]==2){
    $aaa  = "SELECT `pID` FROM `product` WHERE `ProductName`= ?";
    $bbb = $db_link->prepare($aaa);
    $bbb->bind_param("s", $_POST["BrandName"]);
    $bbb->bind_result($ID);	
	  $bbb->fetch();
    $bbb->execute();
    $bbb->close();
    // echo $ID;
    $qqq  = "INSERT INTO `phone` (`productID`) VALUES (?)";
    $insertion = $db_link->prepare($qqq);
    $insertion->bind_param("i", $ID);
    $insertion->execute();
    $insertion->close();
    $db_link->close();
  }

	header("Location: product_add.php");
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>網站會員系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){
	if(document.formJoin.BrandName.value==""){
		alert("請填寫商品廠牌!");
		document.formJoin.m_birthday.focus();
		return false;}
	if(document.formJoin.ProductName.value==""){
		alert("請填寫商品名稱!");
		document.formJoin.m_email.focus();
		return false;}
	if(document.formJoin.Price.value==""){
		alert("請填寫商品價格!");
		document.formJoin.m_email.focus();
		return false;}
	if(document.formJoin.Quantity.value==""){
		alert("請填寫商品數量!");
		document.formJoin.m_email.focus();
		return false;}
	//if(!checkmail(document.formJoin.m_email)){
	//	document.formJoin.m_email.focus();
	//	return false;}
	return confirm('確定送出嗎？');
}
</script>
</head>

<body>
<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="1")){?>
<script language="javascript">
alert('商品新增成功\n');
window.location.href='product_add.php';		  
</script>
<?php }?>
<table width="780" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
  <td height="150" align="center" background="images/mlogo.jpg" class="tdbline"></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
          <p class="title">新增商品</p>
		  
          <div class="dataDiv">
            <hr size="1" />
            <p class="heading">商品資料</p>
            <p><strong>商品廠牌</strong>：
            <input name="BrandName" type="text" class="normalinput" id="BrandName">
            <font color="#FF0000">*</font><br></p>
            <p><strong>商品名稱</strong>：
            <input name="ProductName" type="text" class="normalinput" id="ProductName">
            <font color="#FF0000">*</font><br></p>
            <p><strong>商品價格</strong>：
            <input name="Price" type="text" class="normalinput" id="Price">
            <font color="#FF0000">*</font> <br><span class="smalltext">USD</span></p>
            <p><strong>商品數量</strong>：
            <input name="Quantity" type="text" class="normalinput" id="Quantity">
            <font color="#FF0000">*</font> <br>
			      <p><strong>是否二手</strong>：
            <input name="SecondHand" type="radio" value="1">是
            <input name="SecondHand" type="radio" value="0" checked>否
            <font color="#FF0000">*</font></p>
            <p><strong>商品類型</strong>：
            <input name="ptype" type="radio" value="2" id="ptype">手機
            <input name="ptype" type="radio" value="1" id="ptype">平板電腦
            <input name="ptype" type="radio" value="0" id="ptype" checked>個人電腦
            <font color="#FF0000">*</font></p>
            <p><strong>商品簡述</strong>：
            <textarea id="Description" name="Description"></textarea>
            <br><span class="smalltext">請簡單敘述商品狀態</span></p>
            <p> <font color="#FF0000">*</font> 表示為必填的欄位</p>
          <hr size="1" />
          <p align="center">
            <input name="action" type="hidden" id="action" value="join">
            <input type="submit" name="Submit2" value="送出申請">
            <input type="reset" name="Submit3" value="重設資料">
            <!-- <input type="button" name="Submit" value="回上一頁" onClick="window.history.back();"> -->
          </p>
        </form></td>
        <td width="200">
          <br>
        <div class="actionDiv"><p align="right"><?php echo $row_RecMember["Username"];?>，歡迎！ | <a href="?logout=true">登出系統</a></p></div>
        <!-- <div class="regbox">
          <p class="heading"><strong>填寫資料注意事項：</strong></p>
          <ol>
            <li> 請提供您本人正確、最新及完整的資料。 </li>
            <li> 在欄位後方出現「*」符號表示為必填的欄位。</li>
            <li>填寫時請您遵守各個欄位後方的補助說明。</li>
            <li>關於您的會員註冊以及其他特定資料，本系統不會向任何人出售或出借你所填寫的個人資料。</li>
          </ol>
          </div>
        <div class="boxbl"></div><div class="boxbr"></div></td> -->
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>